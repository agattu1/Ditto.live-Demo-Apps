<div class="menu">
    <ul>
        <li><a href="<?php echo (strpos($_SERVER['SCRIPT_NAME'], '/sales/') !== false) ? '../index.php' : 'index.php'; ?>">Home</a></li>
        <li><a href="<?php echo (strpos($_SERVER['SCRIPT_NAME'], '/sales/') !== false) ? 'tasks.php' : 'sales/tasks.php'; ?>" class="<?php echo $currentPage == 'tasks' ? 'active' : ''; ?>">Tasks</a></li>
        <li><a href="<?php echo (strpos($_SERVER['SCRIPT_NAME'], '/sales/') !== false) ? 'leads.php' : 'sales/leads.php'; ?>" class="<?php echo $currentPage == 'leads' ? 'active' : ''; ?>">Leads</a></li>
        <li><a href="<?php echo (strpos($_SERVER['SCRIPT_NAME'], '/sales/') !== false) ? 'opportunities.php' : 'sales/opportunities.php'; ?>" class="<?php echo $currentPage == 'opportunities' ? 'active' : ''; ?>">Opportunities</a></li>
        <?php if ($_SESSION['user_role'] === 'manager'): ?>
        <li><a href="<?php echo (strpos($_SERVER['SCRIPT_NAME'], '/sales/') !== false) ? 'customerwon.php' : 'sales/customerwon.php'; ?>" class="<?php echo $currentPage == 'customerwon' ? 'active' : ''; ?>">Customers/Won</a></li>
        <?php endif; ?>
        <li style="float:right">
            <span>Logged in as: <?php echo htmlspecialchars($_SESSION['username']); ?> (<?php echo htmlspecialchars($_SESSION['user_role']); ?>)</span>
            <a href="<?php echo (strpos($_SERVER['SCRIPT_NAME'], '/sales/') !== false) ? '../logout.php' : 'logout.php'; ?>">Logout</a>
        </li>
    </ul>


    
<!-- NEWLY ADDED TO MENU -->
<div class="search-container">
    <input type="text" id="searchInput" placeholder="Search contacts, users, notes...">
    <button onclick="performSearch()">Search</button>
    <div id="searchResults"></div>
</div>

<script>
function performSearch() {
    const query = document.getElementById('searchInput').value.trim();
    const resultsContainer = document.getElementById('searchResults');
    
    if (!query) {
        resultsContainer.innerHTML = '<p>Please enter a search term</p>';
        resultsContainer.style.display = 'block';
        return;
    }

    // Show loading state
    resultsContainer.innerHTML = '<p>Searching...</p>';
    resultsContainer.style.display = 'block';
    
    // Determine correct path to search.php
    const isSalesPage = window.location.pathname.includes('/sales/');
    const searchPath = isSalesPage ? '../search.php' : 'search.php';
    
    fetch(`${searchPath}?q=${encodeURIComponent(query)}`)
        .then(response => {
            if (!response.ok) throw new Error('Network response was not ok');
            return response.json();
        })
        .then(data => {
            if (data.error) {
                resultsContainer.innerHTML = `<p class="error">Error: ${data.message || data.error}</p>`;
                return;
            }
            displayResults(data.results || data);
        })
        .catch(error => {
            resultsContainer.innerHTML = `<p class="error">Search failed: ${error.message}</p>`;
            console.error('Search error:', error);
        });
}

function displayResults(results) {
    const container = document.getElementById('searchResults');
    container.innerHTML = '';
    
    if (!results || results.length === 0) {
        container.innerHTML = '<p>No results found</p>';
        return;
    }
    
    results.forEach(result => {
        const div = document.createElement('div');
        div.className = 'search-result';
        
        // Make results clickable
        div.style.cursor = 'pointer';
        div.onclick = function() {
            if (result.type === 'contact') {
                window.location.href = (window.location.pathname.includes('/sales/') ? 'leads.php' : 'sales/leads.php') + `?id=${result.id}`;
            } else if (result.type === 'note') {
                window.location.href = (window.location.pathname.includes('/sales/') ? 'tasks.php' : 'sales/tasks.php') + `?id=${result.id}`;
            }
        };
        
        if (result.type === 'contact') {
            div.innerHTML = `
                <h3>Contact: ${result.Contact_First} ${result.Contact_Last}</h3>
                <p>Company: ${result.Company || 'N/A'}</p>
                <p>Email: ${result.Email || 'N/A'}</p>
            `;
        } else if (result.type === 'user') {
            div.innerHTML = `
                <h3>User: ${result.Name_First} ${result.Name_Last}</h3>
                <p>Email: ${result.Email || 'N/A'}</p>
            `;
        } else if (result.type === 'note') {
            div.innerHTML = `
                <h3>Note</h3>
                <p>${result.Notes ? result.Notes.substring(0, 100) + '...' : 'No content'}</p>
                <p><small>${result.Date || 'No date'}</small></p>
            `;
        }
        
        container.appendChild(div);
    });
}

// Close results when clicking outside
document.addEventListener('click', function(e) {
    const searchContainer = document.querySelector('.search-container');
    if (!searchContainer.contains(e.target)) {
        document.getElementById('searchResults').style.display = 'none';
    }
});
</script>

<style>
.search-container {
    margin: 20px;
    padding: 20px;
    border: 1px solid #ddd;
}

#searchInput {
    padding: 8px;
    width: 300px;
}

.search-result {
    margin: 10px 0;
    padding: 10px;
    border-bottom: 1px solid #eee;
}
</style>




</div>