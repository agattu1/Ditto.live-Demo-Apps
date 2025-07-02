package live.ditto.inventory

data class ItemModel(
    val itemId: Int,
    val image: Int,
    val title: String,
    val price: Double,
    val detail: String,
    var count: Int = 0
)