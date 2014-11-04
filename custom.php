<?php 

function cls_exhibit_navigation ($exhibitPage = null, $currentPageId) 
{
    if (!$exhibitPage) {
        $exhibitPage = get_current_record('exhibit_page');
    }

    $exhibit = $exhibitPage->getExhibit();
    $pages = $exhibit->getTopPages();
    $html = '';

    $parents = array();
    $pageLoop = $exhibitPage;
    while ($pageLoop->parent_id) {
        $parents[] = $pageLoop->parent_id;
        $pageLoop = $pageLoop->getParent();
    }

    foreach ($parents as $parent) {
        echo 'parent page: ' . $parent . '<br />';
    }

    echo 'This is page: ' . $exhibitPage->id;
    
    $html .= '<ul class="exhibit-page-nav navigation" id="secondary-nav">' . "\n";

    foreach ($pages as $page) {
        $current = (exhibit_builder_is_current_page($page)) ? 'class="current"' : '';
        $pageId = $page->id;
        $html .= "<li $current>" . exhibit_builder_link_to_exhibit($exhibit, $page->title, array(), $page);
        if ($current && $page->countChildPages() > 0) {
            $childPages = $page->getChildPages();
            $html .= '<ul class="child-pages">';
            foreach ($childPages as $childPage) {
                $html .= "<li>" . exhibit_builder_link_to_exhibit($exhibit, $childPage->title, array(), $childPage) . '</li>';
            }
            $html .= '</ul>';
        }
        elseif (in_array($pageId, $parents)) {
            $children = $page->getChildPages();
            $html .= '<ul class="child-pages">' . "\n";
            foreach ($children as $child) {
                $grandchildren = $child->getChildPages();
                $childId = $child->id;
                $current = (exhibit_builder_is_current_page($child)) ? 'class="current"' : '';
                $html .= "<li $current>" . exhibit_builder_link_to_exhibit($exhibit, $child->title, array(), $child);
                if ($current && $grandchildren > 0) {
                    $html .= '<ul class="grandchild-pages">';
                    foreach ($grandchildren as $grandchild) {
                        $html .= "<li>" . exhibit_builder_link_to_exhibit($exhibit, $grandchild->title, array(), $grandchild) . '</li>';
                    }
                    $html .= '</ul>';
                }
                /*elseif (in_array($childId, $parents) {
                    # code...
                }*/
                $html .= '</li>' . "\n";
            }
            $html .= '</ul>' . "\n";
        }
        $html .= '</li>' . "\n";
    }

    $html .= '</ul>' . "\n";
    # $html = apply_filters('exhibit_builder_page_nav', $html);
    return $html;
}

function emiglio_exhibit_builder_page_nav($exhibitPage = null)
{
    if (!$exhibitPage) {
        if (!($exhibitPage = get_current_record('exhibit_page', false))) {
            return;
        }
    }

    $exhibit = $exhibitPage->getExhibit();
    $html = '<ul class="exhibit-page-nav navigation" id="secondary-nav">' . "\n";
    $pages = $exhibit->getTopPages();
    $htmlChild = '';
    foreach ($pages as $page) {
        $current = (exhibit_builder_is_current_page($page)) ? 'class="current"' : '';
        $html .= "<li $current>" . exhibit_builder_link_to_exhibit($exhibit, $page->title, array(), $page);
        if ($current) {
            if ($page->countChildPages() > 0) {
                $childPages = $page->getChildPages();
                $html .= '<ul class="child-pages">';
                    foreach ($childPages as $childPage) {
                        $html .= "<li>" . exhibit_builder_link_to_exhibit($exhibit, $childPage->title, array(), $childPage) . '</li>';
                    }
                $html .= '</ul>';
            }
        }
        else {
            if ($page->countChildPages() > 0) {
                $childPages = $page->getChildPages();
                $htmlChild = '<ul class="child-pages">';
                $currentChild = '';
                    foreach ($childPages as $childPage) {
                        $current = (exhibit_builder_is_current_page($childPage)) ? 'class="current"' : '';
                        $currentChild .= $current;
                        $htmlChild .= "<li $current>" . exhibit_builder_link_to_exhibit($exhibit, $childPage->title, array(), $childPage) . '</li>';
                    }
                $htmlChild .= '</ul>';
                if (!$currentChild) {
                    $htmlChild = '';
                }
            }
        }
        $html .= $htmlChild . '</li>';
        $htmlChild = '';
    }
    $html .= '</ul>' . "\n";
    $html = apply_filters('exhibit_builder_page_nav', $html);
    return $html;
}

function emiglio_exhibit_builder_page_summary($exhibitPage = null)
{
    if(!$exhibitPage) {
        $exhibitPage = get_current_record('exhibit_page');
    }

    $html = '<li>'
            . '<a href="' . exhibit_builder_exhibit_uri(get_current_record('exhibit'), $exhibitPage) . '">'
            . metadata($exhibitPage, 'title') . '</a>'
            . '</li>';
    return $html;
}

function return_to_exhibit(){
    $back = htmlspecialchars($_SERVER['HTTP_REFERER']);
    $html = '<a href="' . $back . '">&larr; Back to the Exhibit</a>';
    return $html;
}

function emiglio_exhibit_builder_summary_accordion($exhibitPage = null)
{
    if (!$exhibitPage) {
        $exhibitPage = get_current_record('exhibit_page');
    }

    $html = '<h3>' . metadata($exhibitPage, 'title') .'</h3>';

    $children = $exhibitPage->getChildPages();
    if ($children) {
        $html .= '<div><a href="' . exhibit_builder_exhibit_uri(get_current_record('exhibit'), $exhibitPage) 
                . '">' . metadata($exhibitPage, 'title') .'</a><ul>';
        foreach ($children as $child) {
            $html .= exhibit_builder_page_summary($child);
            release_object($child);
        }
        $html .= '</ul></div>';
    }
    else {
        $html .= '<div><a href="' . exhibit_builder_exhibit_uri(get_current_record('exhibit'), $exhibitPage) 
                . '">' . metadata($exhibitPage, 'title') .'</a></div>';
    }
    return $html;
}
?>
