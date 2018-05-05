<div class="post-search-panel">
    <input type="text" id="keywords" placeholder="Search Here <?= !empty($pageheading) ? $pageheading : '' ?> ..." onkeyup="searchFilter()"/>
    <select class="select" id="sortBy" onchange="searchFilter()">
        <option value="">Sort By</option>
        <option value="asc">Ascending</option>
        <option value="desc">Descending</option>
    </select>
</div>