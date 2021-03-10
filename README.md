## Description
Plugin extends JetSmartFilters functionality. It allows to search posts by post ID in the Search filter.
Plugin not depends on Search by control from filter options - it will allow to search also by post ID with both variants - Default WordPress search and By Custom Field (from Query Variable).

Dy default will search by exact match - if put 34 into search field, it founds only posts with ID 34, but not 341, 134 or 1341. If you want to search by partial match, add this code into the end of functions.php file of your active theme:

```php
	add_filter( 'jet-smart-filters-search-by-id/exact-match', '__return_false' );
```

## How to use
- Download, install and activate plugin;
- Plugin not requires any configuration - after activation all active Search filters starts trying to search posts also by post ID