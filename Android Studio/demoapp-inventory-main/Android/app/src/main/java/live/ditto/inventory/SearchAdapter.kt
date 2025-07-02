package live.ditto.inventory

import android.view.LayoutInflater
import android.view.View
import android.view.ViewGroup
import android.widget.ImageView
import android.widget.TextView
import androidx.recyclerview.widget.DiffUtil
import androidx.recyclerview.widget.ListAdapter
import androidx.recyclerview.widget.RecyclerView

class SearchAdapter : ListAdapter<ItemModel, SearchAdapter.SearchViewHolder>(DiffCallback) {

    // Optional click listener if you want item clicks
    var onItemClick: ((ItemModel) -> Unit)? = null

    object DiffCallback : DiffUtil.ItemCallback<ItemModel>() {
        override fun areItemsTheSame(oldItem: ItemModel, newItem: ItemModel): Boolean = 
            oldItem.itemId == newItem.itemId
        override fun areContentsTheSame(oldItem: ItemModel, newItem: ItemModel): Boolean = 
            oldItem == newItem
    }

    override fun onCreateViewHolder(parent: ViewGroup, viewType: Int): SearchViewHolder {
        val view = LayoutInflater.from(parent.context)
            .inflate(R.layout.item_search_result, parent, false)
        return SearchViewHolder(view)
    }

    override fun onBindViewHolder(holder: SearchViewHolder, position: Int) {
        holder.bind(getItem(position))
    }

    inner class SearchViewHolder(view: View) : RecyclerView.ViewHolder(view) {
        private val title: TextView = view.findViewById(R.id.item_title)
        private val detail: TextView = view.findViewById(R.id.item_detail)
        private val count: TextView = view.findViewById(R.id.item_count)
        private val image: ImageView = view.findViewById(R.id.item_image)

        fun bind(item: ItemModel) {
            title.text = item.title
            detail.text = item.detail
            count.text = item.count.toString()
            image.setImageResource(item.image)
            
            itemView.setOnClickListener {
                onItemClick?.invoke(item)
            }
        }
    }
}