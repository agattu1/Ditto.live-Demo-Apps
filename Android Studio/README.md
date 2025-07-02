## TODO   

### Andriod App

I first played around with the [Inventory demo app](https://github.com/getditto/demoapp-inventory) from Ditto which showed me Ditto real-time sync via peer to peer devices in the mesh of food inventory counter. I had to initalize my App ID and Auth URL from the Ditto Portal to connect via SDK (I called it [outreachable](https://portal.ditto.live/app/outreachable/connect)).
![image](https://github.com/user-attachments/assets/122d50b4-af2e-4e79-b22e-cf7ddd022ec3)

I listed the creds in the `secure/debug_creds.properties` file so Android looks at that .env file. When gradle restores packages, it should auto load in the information from the .env file to the BuildConfig. DittoManager then reads these values and uses them to connect to Ditto.

For more information, see the [build.gradle](https://github.com/getditto/demoapp-inventory/blob/main/Android/app/build.gradle#L20) file.

Here is a systematic and chronological breakdown of the key changes made to enable working search functionality in the Ditto Inventory demo app:

Here is a **systematic and chronological breakdown** of the key changes made to enable working search functionality in the Ditto Inventory demo app:

---

### ✅ 1. **Manifest Configuration**

**File:** `AndroidManifest.xml`

* **Added** the `SearchActivity`:

  ```xml
  <activity android:name="live.ditto.inventory.SearchActivity"/>
  ```
* **Ensured Permissions**:

  * Bluetooth, location, internet, and network-related permissions were declared, which are needed for Ditto syncing.

---

### ✅ 2. **UI Layout for Search**

**File:** `item_search_result.xml` (name inferred from adapter)

* **Defined** the layout of each search result item, including:

  * `ImageView` for product image.
  * `TextView` for title, detail, and count.
  * Used `ConstraintLayout` for responsiveness.

---

### ✅ 3. **Created `SearchActivity.kt`**

**File:** `SearchActivity.kt`

* **Functionality:**

  * Initializes RecyclerView and adapter (`SearchAdapter`).
  * Adds a debounced `TextWatcher` to input field.
  * On every text change:

    * Closes previous `DittoStoreObserver`.
    * Initiates a new observer via `DittoManager.observeSearchResults()`.
  * Converts matched documents to `ItemModel` and submits to adapter.

---

### ✅ 4. **Created `SearchAdapter.kt`**

**File:** `SearchAdapter.kt`

* **Purpose:**

  * Displays search result items using a custom layout.
  * Supports click listener (optional).
  * Efficient updates using `DiffUtil`.

---

### ✅ 5. **Major Overhaul in `DittoManager.kt`**

**File:** `DittoManager.kt`

* ✅ **Refactored `observeSearchResults()`**:

  * **Old issue:** You initially used `".contains($0)"`, which caused runtime `ParseError`.
  * **Fix:** Changed to proper DQL `LIKE` usage:

    ```kotlin
    val query = """
        SELECT * 
        FROM inventories 
        WHERE title LIKE :query OR detail LIKE :query
    """
    val params = mapOf("query" to "%$queryText%")
    ```
  * **Returns:** `DittoStoreObserver` instead of `CombinedLiveQuery`.
  * **Handles Result Conversion:** Converts each result to a `DittoDocument` using `_id` lookup and maps to corresponding `ItemModel`.
 
  * **COLLECTIONS**
  * Ditto stores data records as documents (JSON-like) which are gathered together in collections.
  *  An application stores one or more collection of documents ( All DOCS are in Collections)  Internally these documents are CRDTs, which are a binary representation of JSON doc
  *  Query Collections : DATABASE INTERACTIONS OCCUR W/ COLLECTIONS NOT DOCS!


* ✅ **Logging:** Added logs for debugging Ditto startup, data loading, and search events.

* ✅ **Improved Defensive Checks:** Early return if Ditto is uninitialized.

* ✅ **Removed Invalid References:**

  * Removed incorrect usage of `DittoObserver` and `findByID` which do not exist in Ditto SDK.
 
  * public final class DittoStore
Provides access to execute Ditto queries, work with DittoCollections and a write transaction API

-> 
public final class DittoStoreObserver
    : Closeable
A store observer invokes an observation handler whenever results for its query change.
Create a store observer by calling DittoStore.registerObserver. The store observer will remain active until the owning Ditto instance goes out of scope or the DittoStoreObserver.close is called


---

### ✅ 6. **Hooked Search Button in MainActivity**

**File:** `MainActivity.kt`

* **Method Added**:

  ```kotlin
  fun openSearchScreen(view: View) {
      startActivity(Intent(this, SearchActivity::class.java))
  }
  ```
* **Ensure Button in Layout**:

  * Search icon or button in XML must be set to `onClick="openSearchScreen"`.

---

### ✅ 7. **Visual Enhancements**

* ✳️ **UI Detail Improvements** in `itemsForView`:

  * Emojis and polished descriptions (e.g., `"Cold can of Coke 🧊"`).
* ✳️ **Live Count Sync:** Updates UI with real-time count changes.

---

### ✅ 8. **Live Filtering Logic**

* **In Adapter:**

  * Filters results based on `_id` and dynamically updates counts.
* **Thread-Safe Execution:**

  * UI updates use `runOnUiThread`.

---

### ✅ 9. **Closing Observers**

* **In `onDestroy()` of `SearchActivity`:**

  ```kotlin
  override fun onDestroy() {
      searchObserver?.close()
      super.onDestroy()
  }
  ```

---

## Summary of Core Fixes

| Area               | Fix/Change                                                             |
| ------------------ | ---------------------------------------------------------------------- |
| **Crash Fix**      | Corrected Ditto query syntax from `.contains()` to `LIKE :query`.      |
| **Observer API**   | Switched from invalid observer types to `DittoStoreObserver`.          |
| **UI/UX**          | Added debounce, consistent UI updates, and real-time sync integration. |
| **Manifest**       | Registered `SearchActivity` and ensured necessary permissions.         |
| **Debugging Logs** | Added logs for startup, sync, and search result tracing.               |
| **Removed Errors** | Eliminated usage of `findByID` and `DittoObserver` which don’t exist.  |

---

## Future work...

If I could euphoria a new model for the inventory api for scaling when we have tons of food inventory added into stock and nearby locations which offer that inventory food item specifically. 

Access pattern which you might see:

When the front end service wants to get all of the restaurants nearby with their food inventory to show it to the user, we are going to make it call the list resturants api & return all the resturants we have 

This could break tho x....x What if we have 10 GB data of resturants in the DB?
Aws Lambda caps out at 6 MB ... if more resturants it will break

`Solution` : Pagination

When the front end wants restaurants, We're just gonna return their list up the 10 nearest restaurants plus a token they can use to make a second API call to get the 10 restaurants after that.
Users wont care as they may only want to look at the inventory in the 10 nearest resturants anyway... (Its like 2nd pg of Google)