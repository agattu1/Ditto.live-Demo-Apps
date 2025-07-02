package live.ditto.inventory

import android.content.Context
import android.util.Log
import live.ditto.*
import live.ditto.android.DefaultAndroidDittoDependencies

object DittoManager {

    interface ItemUpdateListener {
        fun setInitial(items: List<ItemModel>)
        fun updateCount(index: Int, count: Int)
    }

    lateinit var itemUpdateListener: ItemUpdateListener

    var ditto: Ditto? = null; private set
    private const val COLLECTION_NAME = "inventories"
    private var collection: DittoCollection? = null

    private var subscription: DittoSyncSubscription? = null
    private var liveQuery: DittoLiveQuery? = null

    private const val APP_ID = BuildConfig.APP_ID
    private const val ONLINE_AUTH_TOKEN = BuildConfig.ONLINE_AUTH_TOKEN

    internal suspend fun startDitto(context: Context) {
        DittoLogger.minimumLogLevel = DittoLogLevel.DEBUG
        Log.d("DittoInit", "Starting Ditto SDK...")

        val dependencies = DefaultAndroidDittoDependencies(context)
        ditto = Ditto(dependencies, DittoIdentity.OnlinePlayground(dependencies, APP_ID, ONLINE_AUTH_TOKEN, false))

        try {
            ditto?.disableSyncWithV3()
            ditto?.store?.execute("ALTER SYSTEM SET mesh_chooser_avoid_redundant_bluetooth = false")
            ditto?.startSync()
            Log.d("DittoInit", "Ditto started successfully")
        } catch (e: Exception) {
            Log.e("Ditto", "Failed to start Ditto: ${e.message}", e)
        }

        collection = ditto?.store?.collection(COLLECTION_NAME)
        Log.d("DittoInit", "Collection '$COLLECTION_NAME' initialized: ${collection != null}")
        
        observeItems()
        insertDefaultDataIfAbsent()
        logDittoStatus() // Log initial status
    }

    private fun logDittoStatus() {
        Log.d("DittoStatus", "SDK Version: ${ditto?.sdkVersion ?: "N/A"}")
        Log.d("DittoStatus", "Collections: ${ditto?.store?.collectionNames()?.joinToString() ?: "None"}")
        Log.d("DittoStatus", "Inventory Docs: ${collection?.findAll()?.exec()?.size ?: 0}")
    }

    internal fun increment(itemId: Int) {
        collection?.findById(itemId)?.update {
            it?.get("counter")?.counter?.increment(1.0)
        }
    }

    internal fun decrement(itemId: Int) {
        collection?.findById(itemId)?.update {
            it?.get("counter")?.counter?.increment(-1.0)
        }
    }

    internal val sdkVersion: String?
        get() = ditto?.sdkVersion

    private fun insertDefaultDataIfAbsent() {
        ditto?.store?.write { transaction ->
            val scope = transaction.scoped(COLLECTION_NAME)
            for (viewItem in itemsForView) {
                val doc = collection?.findById(viewItem.itemId)?.exec()
                if (doc == null) {
                    scope.upsert(
                        mapOf(
                            "_id" to viewItem.itemId,
                            "counter" to DittoCounter(),
                            "title" to viewItem.title,
                            "detail" to viewItem.detail,
                            "price" to viewItem.price,
                            "image" to viewItem.image
                        ),
                        writeStrategy = DittoWriteStrategy.InsertDefaultIfAbsent
                    )
                    Log.d("DittoData", "Inserted item: ${viewItem.title}")
                } else {
                    viewItem.count = doc["counter"].intValue
                }
            }
        }
    }

    private fun observeItems() {
        val query = collection?.findAll()
        subscription = ditto?.sync?.registerSubscription(query = "SELECT * FROM inventories")
        liveQuery = query?.observeLocal { docs, event ->
            when (event) {
                is DittoLiveQueryEvent.Initial -> {
                    Log.d("DittoObserve", "Initial data: ${docs.size} items")
                    itemUpdateListener.setInitial(itemsForView.toMutableList())
                }
                is DittoLiveQueryEvent.Update -> {
                    event.updates.forEach { index ->
                        val doc = docs[index]
                        val count = doc["counter"].intValue
                        itemUpdateListener.updateCount(index, count)
                    }
                }
            }
        }
    }

    internal fun observeSearchResults(
        queryText: String,
        callback: (List<DittoDocument>) -> Unit
    ): DittoStoreObserver? {  // Changed return type
        if (ditto == null) {
            Log.e("DittoSearch", "Ditto not initialized!")
            return null
        }

        try {
            val query = """
                SELECT * 
                FROM $COLLECTION_NAME 
                WHERE title LIKE :query OR detail LIKE :query
            """.trimIndent()
            
            val params = mapOf("query" to "%$queryText%")
            Log.d("DittoSearch", "Executing DQL: $query with params: $params")
            
            // Return DittoStoreObserver directly
            return ditto!!.store.registerObserver(query, params) { result ->
                try {
                    Log.d("DittoSearch", "Received ${result.items.size} results")
                    
                    // Convert QueryResultItems to DittoDocuments
                    val docs = result.items.mapNotNull { item ->
                        try {
                            val id = item.value["_id"]?.toString()?.toIntOrNull()
                            id?.let { collection?.findById(it)?.exec() }
                        } catch (e: Exception) {
                            Log.e("DittoSearch", "Error converting item", e)
                            null
                        }
                    }
                    
                    callback(docs.filterNotNull())
                } catch (e: Exception) {
                    Log.e("DittoSearch", "Error processing results", e)
                }
            }
        } catch (e: Exception) {
            Log.e("DittoSearch", "Search failed", e)
            return null
        }
    }

    val itemsForView = arrayOf(
        ItemModel(0, R.drawable.coke, "Coca-Cola", 2.50, "Cold can of Coke 🧊"),
        ItemModel(1, R.drawable.drpepper, "Dr. Pepper", 2.50, "Chilled bottle of Dr. Pepper 🩺"),
        ItemModel(2, R.drawable.lays, "Lay's Classic", 3.99, "Original Classic Potato Chips 🍟"),
        ItemModel(3, R.drawable.brownies, "Brownies", 6.50, "Brownies, Sugar Free Version 🍫"),
        ItemModel(4, R.drawable.blt, "Classic BLT Egg", 2.50, "Contains egg, bean patty, veggies 🥚"),
        ItemModel(5, R.drawable.protien, "Protein Bar", 1.25, "High energy midday snack with nuts 💪"),
        ItemModel(6, R.drawable.chipotle, "Chipotle", 9.25, "Veggie rice bowl with queso, guac, beans 🍚 "),
    )
}