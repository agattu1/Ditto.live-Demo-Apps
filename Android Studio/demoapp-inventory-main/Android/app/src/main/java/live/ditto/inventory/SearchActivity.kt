package live.ditto.inventory

import android.os.Bundle
import android.util.Log  // Added Log import
import android.text.Editable
import android.text.TextWatcher
import android.widget.EditText
import androidx.appcompat.app.AppCompatActivity
import androidx.recyclerview.widget.LinearLayoutManager
import androidx.recyclerview.widget.RecyclerView
import live.ditto.DittoStoreObserver

class SearchActivity : AppCompatActivity() {

    private lateinit var adapter: SearchAdapter
    private var searchObserver: DittoStoreObserver? = null
    private var currentQuery: String = ""

    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_search)

        val searchInput = findViewById<EditText>(R.id.search_input)
        val resultList = findViewById<RecyclerView>(R.id.result_list)

        adapter = SearchAdapter()
        resultList.layoutManager = LinearLayoutManager(this)
        resultList.adapter = adapter

        // Add debounce to prevent rapid searches
        searchInput.addTextChangedListener(object : TextWatcher {
            private var lastText: String = ""
            private val debounceDelay = 300L // 300ms delay
            
            override fun afterTextChanged(s: Editable?) {
                val newText = s?.toString()?.trim() ?: ""
                if (newText != lastText) {
                    lastText = newText
                    searchInput.removeCallbacks(searchRunnable)
                    searchInput.postDelayed(searchRunnable, debounceDelay)
                }
            }

            override fun beforeTextChanged(s: CharSequence?, start: Int, count: Int, after: Int) {}
            override fun onTextChanged(s: CharSequence?, start: Int, before: Int, count: Int) {}
            
            private val searchRunnable = Runnable {
                performSearch(lastText)
            }
        })
    }

    private fun performSearch(query: String) {
        // Clear previous observer
        searchObserver?.close()  // Changed from stop() to close()
        searchObserver = null
        
        currentQuery = query
        
        if (query.isEmpty()) {
            adapter.submitList(emptyList())
            return
        }
        
        searchObserver = DittoManager.observeSearchResults(query) { docs ->
            runOnUiThread {
                // Only update if query hasn't changed during search
                if (query == currentQuery) {
                    val items = docs.mapNotNull { doc ->
                        val id = doc["_id"].intValue
                        DittoManager.itemsForView.find { it.itemId == id }?.copy(
                            count = doc["counter"].intValue
                        )
                    }
                    adapter.submitList(items)
                    Log.d("SearchUpdate", "Displaying ${items.size} results for '$query'")
                }
            }
        }
    }

    override fun onDestroy() {
        searchObserver?.close()  // Changed from stop() to close()
        super.onDestroy()
    }
}