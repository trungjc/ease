<select id="motopress-pages">
    <optgroup label="<?php echo $lang->pages; ?>" id="pages">
    <?php
        global $wooActive;
        $wooActive = is_plugin_active('woocommerce/woocommerce.php');
        global $jigoActive;
        $jigoActive = is_plugin_active('jigoshop/jigoshop.php');

        global $wpdb;

        //woo
        $wooResults = $wpdb->get_results("SELECT option_value, option_name FROM $wpdb->options WHERE option_name LIKE 'woocommerce_%_page_id'");
        global $wooPages;
        $wooPages = array();
        if (!empty($wooResults)) {
            foreach ($wooResults as $wooResult) {
                if (!empty($wooResult->option_value)) {
                    $wooPages[(int)$wooResult->option_value] = $wooResult->option_name;
                }
            }
        }

        //jigo
        $jigoResults = $wpdb->get_results("SELECT option_value, option_name FROM $wpdb->options WHERE option_name LIKE 'jigoshop_%_page_id'");
        global $jigoPages;
        $jigoPages = array();
        if (!empty($jigoResults)) {
            foreach ($jigoResults as $jigoResult) {
                if (!empty($jigoResult->option_value)) {
                    $jigoPages[(int)$jigoResult->option_value] = $jigoResult->option_name;
                }
            }
        }

        //only jigo
        global $onlyJigoPages;
        $onlyJigoPages = array_diff_key($jigoPages, $wooPages);

        $pageForPosts = (int)get_option('page_for_posts');
        $pageOnFront = (int)get_option('page_on_front');

        $pages = get_pages(array('sort_column' => 'menu_order', 'parent' => 0));
        foreach ($pages as $page) {
            $file = get_post_meta($page->ID, '_wp_page_template', true);
            $hideTemplate = false;
            $frontPage = false;

            if (array_key_exists($page->ID, $onlyJigoPages)) {
                continue;
            } elseif (array_key_exists($page->ID, $wooPages)) {
                if ($wooActive) {
                    if (strpos($wooPages[$page->ID], 'logout')) {
                        continue;
                    } elseif (strpos($wooPages[$page->ID], 'shop')) {
                        $hideTemplate = true;
                        if (file_exists($motopressSettings['theme_root'].'/'.$motopressSettings['current_theme'].'/woocommerce.php')) {
                            update_post_meta($page->ID, '_wp_page_template', '_woocommerce.php');
                            $file = 'woocommerce.php';
                        } elseif (file_exists($motopressSettings['theme_root'].'/'.$motopressSettings['current_theme'].'/archive-product.php')) {
                            $file = 'archive-product.php';
                        } elseif (file_exists($motopressSettings['theme_root'].'/'.$motopressSettings['current_theme'].'/woocommerce/archive-product.php')) {
                            $file = 'woocommerce/archive-product.php';
                        } else {
                            getChildPages($page->ID, $pageForPosts, $pageOnFront);
                            continue;
                        }
                    } else {
                        if (empty($file) || $file == 'default') {
                            $file = 'page.php';
                        }
                    }
                } elseif ($jigoActive) continue;
            } else {
                if ($page->ID == $pageForPosts) {
                    $file = 'index.php';
                    $hideTemplate = true;
                } else {
                    $filePath = $motopressSettings['theme_root'] . '/' . $motopressSettings['current_theme'] . '/' . $file;
                    if (empty($file) || $file == 'default' || !file_exists($filePath)) {
                        $slugFile = 'page-' . $page->post_name . '.php';
                        $slugPath = $motopressSettings['theme_root'] . '/' . $motopressSettings['current_theme'] . '/' . $slugFile;

                        $idFile = 'page-' . $page->ID . '.php';
                        $idPath = $motopressSettings['theme_root'] . '/' . $motopressSettings['current_theme'] . '/' . $idFile;

                        if (file_exists($slugPath)) {
                            $file = $slugFile;
                        } else if(file_exists($idPath)) {
                            $file = $idFile;
                        } else {
                            $file = 'page.php';
                        }
                    }
                }
            }

            if ($page->ID == $pageOnFront) {
                $frontPage = true;
            }

            showOption(get_page_link($page->ID), $file, $page->ID, $hideTemplate, $frontPage, $page->post_title);
            getChildPages($page->ID, $pageForPosts, $pageOnFront);
        }

        function getChildPages($id, $pageForPosts, $pageOnFront) {
            global $motopressSettings;
            global $wooActive;
            global $jigoActive;
            global $wooPages;
            global $onlyJigoPages;

            $childs = get_pages(array('child_of' => $id, 'parent' => $id, 'sort_column' => 'menu_order'));
            foreach($childs as $child) {
                $file = get_post_meta($child->ID, '_wp_page_template', true);
                $hideTemplate = false;
                $frontPage = false;

                if (array_key_exists($child->ID, $onlyJigoPages)) {
                    continue;
                } elseif (array_key_exists($child->ID, $wooPages)) {
                    if ($wooActive) {
                        if (strpos($wooPages[$child->ID], 'logout')) {
                            continue;
                        } elseif (strpos($wooPages[$child->ID], 'shop')) {
                            $hideTemplate = true;
                            if (file_exists($motopressSettings['theme_root'].'/'.$motopressSettings['current_theme'].'/woocommerce.php')) {
                                update_post_meta($child->ID, '_wp_page_template', '_woocommerce.php');
                                $file = 'woocommerce.php';
                            } elseif (file_exists($motopressSettings['theme_root'].'/'.$motopressSettings['current_theme'].'/archive-product.php')) {
                                $file = 'archive-product.php';
                            } elseif (file_exists($motopressSettings['theme_root'].'/'.$motopressSettings['current_theme'].'/woocommerce/archive-product.php')) {
                                $file = 'woocommerce/archive-product.php';
                            } else {
                                getChildPages($child->ID, $pageForPosts, $pageOnFront);
                                continue;
                            }
                        } else {
                            if (empty($file) || $file == 'default') {
                                $file = 'page.php';
                            }
                        }
                    } elseif ($jigoActive) continue;
                } else {
                    if ($child->ID == $pageForPosts) {
                        $file = 'index.php';
                        $hideTemplate = true;
                    } else {
                        $filePath = $motopressSettings['theme_root'] . '/' . $motopressSettings['current_theme'] . '/' . $file;
                        if (empty($file) || $file == 'default' || !file_exists($filePath)) {
                            $slugFile = 'page-' . $child->post_name . '.php';
                            $slugPath = $motopressSettings['theme_root'] . '/' . $motopressSettings['current_theme'] . '/' . $slugFile;

                            $idFile = 'page-' . $child->ID . '.php';
                            $idPath = $motopressSettings['theme_root'] . '/' . $motopressSettings['current_theme'] . '/' . $idFile;

                            if (file_exists($slugPath)) {
                                $file = $slugFile;
                            } else if(file_exists($idPath)) {
                                $file = $idFile;
                            } else {
                                $file = 'page.php';
                            }
                        }
                    }
                }

                if ($child->ID == $pageOnFront) {
                    $frontPage = true;
                }

                showOption(get_page_link($child->ID), $file, $child->ID, $hideTemplate, $frontPage, $child->post_title);
                getChildPages($child->ID, $pageForPosts, $pageOnFront);
            }
        }
    ?>
    </optgroup>

    <optgroup label="<?php echo $lang->system; ?>" id="system">
        <?php
            /*
             * Blog
             */
            if($pageForPosts == 0) {
                $blogPath = explode('/', get_home_template());
                $blogFile = end($blogPath);
                showOption(home_url(), $blogFile, null, false, false, 'Blog');
            }

            /*
             * 404
             */
            $notFoundTemplate = get_404_template();
            if(!empty($notFoundTemplate)) {
                $notFoundPath = explode('/', $notFoundTemplate);
                $notFoundFile = end($notFoundPath);

                //TODO: permalink_structure
                $permalinkStructure = get_option('permalink_structure');
                $str = (empty($permalinkStructure)) ? '/?error=404' : '/error/404';
                $link = home_url($str);

                showOption($link, $notFoundFile, null, false, false, '404');
            }

            /*
             * Search
             */
            $searchTemplate = get_search_template();
            if(!empty($searchTemplate)) {
                $searchPath = explode('/', $searchTemplate);
                $searchFile = end($searchPath);
                $searchQuery = 'search';

                showOption(get_search_link($searchQuery), $searchFile, null, false, false, 'Search');
            }

            $files = array_diff(scandir(TEMPLATEPATH), array('.', '..'));

            $archiveChilds = array('category' => false, 'tag' => false, 'author' => false, 'date' => false, 'archive-posttype' => false);

            /*
             * Category
             */
            $categories = get_categories(array('hide_empty' => 0));
            if (!empty($categories)) {
                $categoriesFromDb = array();
                foreach ($categories as $category) {
                    $categoriesFromDb[$category->cat_ID] = $category->slug;
                }

                //category-slug
                $categorySlugFiles = preg_grep('/^category-(?!\d).+\.php$/is', $files);
                $mergeCategorySlugId = array();
                if (!empty($categorySlugFiles)) {
                    $categorySlugsFromFiles = array();
                    foreach ($categorySlugFiles as $categorySlugFile) {
                        $categorySlugArr = explode('category-', $categorySlugFile);
                        $categorySlugWithExt = explode('.php', end($categorySlugArr));
                        $categorySlug = $categorySlugWithExt[0];

                        $categorySlugsFromFiles[] = $categorySlug;
                        $mergeCategorySlugId[] = $categorySlug;

                        $categorySlugId = array_search($categorySlug, $categoriesFromDb);
                        if ($categorySlugId) {
                            //$loopFile = getLoopFile($categorySlugFile);
                            showOption(get_category_link($categorySlugId), $categorySlugFile,/*$loopFile, 'cat',*/ null, false, false, 'Category ' . $categorySlug);
                        }
                    }
                }

                //category-id
                $categoryIdFiles = preg_grep('/^category-\d+\.php$/is', $files);
                if (!empty($categoryIdFiles)) {
                    foreach ($categoryIdFiles as $categoryIdFile) {
                        $categoryIdArr = explode('category-', $categoryIdFile);
                        $categoryIdWithExt = explode('.php', end($categoryIdArr));
                        $categoryId = (int)$categoryIdWithExt[0];
                        if (array_key_exists($categoryId, $categoriesFromDb) && !in_array($categoriesFromDb[$categoryId], $categorySlugsFromFiles)) {
                            $mergeCategorySlugId[] = $categoriesFromDb[$categoryId];
                            //$loopFile = getLoopFile($categoryIdFile);
                            showOption(get_category_link($categoryId), $categoryIdFile,/*$loopFile, 'cat',*/ null, false, false, 'Category ' . $categoryId);
                        }
                    }
                }

                //category
                $diffCategory = array_diff($categoriesFromDb, $mergeCategorySlugId);
                if (!empty($diffCategory)) {
                    $categoryTemplate = get_category_template();
                    if(!empty($categoryTemplate)) {
                        $categoryPath = explode('/', $categoryTemplate);
                        $categoryFile = end($categoryPath);
                        showOption(get_category_link(key($diffCategory)), $categoryFile, null, false, false, 'Category');
                    } else {
                        $archiveChilds['category'] = true;
                    }
                }
            }


            /*
             * Tag
             */
            $tags = get_tags(array('hide_empty' => 0));
            if (!empty($tags)) {
                $tagsFromDb = array();
                foreach ($tags as $tag) {
                    $tagsFromDb[$tag->term_id] = $tag->slug;
                }

                //tag-slug
                $tagSlugFiles = preg_grep('/^tag-(?!\d).+\.php$/is', $files);
                $mergeTagSlugId = array();
                if (!empty($tagSlugFiles)) {
                    $tagSlugsFromFiles = array();
                    foreach ($tagSlugFiles as $tagSlugFile) {
                        $tagSlugArr = explode('tag-', $tagSlugFile);
                        $tagSlugWithExt = explode('.php', end($tagSlugArr));
                        $tagSlug = $tagSlugWithExt[0];

                        $tagSlugsFromFiles[] = $tagSlug;
                        $mergeTagSlugId[] = $tagSlug;

                        $tagSlugId = array_search($tagSlug, $tagsFromDb);
                        if ($tagSlugId) {
                            showOption(get_tag_link($tagSlugId), $tagSlugFile, null, false, false, 'Tag ' . $tagSlug);
                        }
                    }
                }

                //tag-id
                $tagIdFiles = preg_grep('/^tag-\d+\.php$/is', $files);
                if (!empty($tagIdFiles)) {
                    foreach ($tagIdFiles as $tagIdFile) {
                        $tagIdArr = explode('tag-', $tagIdFile);
                        $tagIdWithExt = explode('.php', end($tagIdArr));
                        $tagId = (int)$tagIdWithExt[0];
                        if (array_key_exists($tagId, $tagsFromDb) && !in_array($tagsFromDb[$tagId], $tagSlugsFromFiles)) {
                            $mergeTagSlugId[] = $tagsFromDb[$tagId];
                            showOption(get_tag_link($tagId), $tagIdFile, null, false, false, 'Tag ' . $tagId);
                        }
                    }
                }

                //tag
                $diffTag = array_diff($tagsFromDb, $mergeTagSlugId);
                if (!empty($diffTag)) {
                    $tagTemplate = get_tag_template();
                    if(!empty($tagTemplate)) {
                        $tagPath = explode('/', $tagTemplate);
                        $tagFile = end($tagPath);
                        showOption(get_tag_link(key($diffTag)), $tagFile, null, false, false, 'Tag');
                    } else {
                        $archiveChilds['tag'] = true;
                    }
                }
            }


            /*
             * Author
             */
            $authors = get_users(array('role' => 'author'));
            $editors = get_users(array('role' => 'editor'));
            $administrators = get_users(array('role' => 'administrator'));

            $authors = array_merge($authors, $editors, $administrators);

            if (!empty($authors)) {
                $authorsFromDb = array();
                foreach ($authors as $author) {
                    $authorsFromDb[$author->ID] = $author->user_nicename;
                }

                //author-slug
                $authorSlugFiles = preg_grep('/^author-(?!\d).+\.php$/is', $files);
                $mergeAuthorSlugId = array();
                if (!empty($authorSlugFiles)) {
                    $authorSlugsFromFiles = array();
                    foreach ($authorSlugFiles as $authorSlugFile) {
                        $authorSlugArr = explode('author-', $authorSlugFile);
                        $authorSlugWithExt = explode('.php', end($authorSlugArr));
                        $authorSlug = $authorSlugWithExt[0];

                        $authorSlugsFromFiles[] = $authorSlug;
                        $mergeAuthorSlugId[] = $authorSlug;

                        $authorSlugId = array_search($authorSlug, $authorsFromDb);
                        if ($authorSlugId) {
                            showOption(get_author_posts_url($authorSlugId), $authorSlugFile, null, false, false, 'Author ' . $authorSlug);
                        }
                    }
                }

                //author-id
                $authorIdFiles = preg_grep('/^author-\d+\.php$/is', $files);
                if (!empty($authorIdFiles)) {
                    foreach ($authorIdFiles as $authorIdFile) {
                        $authorIdArr = explode('author-', $authorIdFile);
                        $authorIdWithExt = explode('.php', end($authorIdArr));
                        $authorId = (int)$authorIdWithExt[0];

                        if (array_key_exists($authorId, $authorsFromDb) && !in_array($authorsFromDb[$authorId], $authorSlugsFromFiles)) {
                            $mergeAuthorSlugId[] = $authorsFromDb[$authorId];
                            showOption(get_author_posts_url($authorId), $authorIdFile, null, false, false, 'Author ' . $authorId);
                        }
                    }
                }

                //author
                $diffAuthor = array_diff($authorsFromDb, $mergeAuthorSlugId);
                if (!empty($diffAuthor)) {
                    $authorTemplate = get_author_template();
                    if(!empty($authorTemplate)) {
                        $authorPath = explode('/', $authorTemplate);
                        $authorFile = end($authorPath);
                        showOption(get_author_posts_url(key($diffAuthor)), $authorFile, null, false, false, 'Author');
                    } else {
                        $archiveChilds['author'] = true;
                    }
                }
            }

            /*
             * Date
             */
            $lastPost = get_posts(array('numberposts' => 1, 'post_type' => 'post'));
            if(!empty($lastPost)) {
                $lastPost = $lastPost[0];
                $lastPostDate = $lastPost->post_date;
                $date = new DateTime($lastPostDate);
                $lastPostYear = (int)$date->format('Y');

                $dateTemplate = get_date_template();
                if (!empty($dateTemplate)) {
                    $datePath = explode('/', $dateTemplate);
                    $dateFile = end($datePath);
                    showOption(get_year_link($lastPostYear), $dateFile, null, false, false, 'Date');
                } else {
                    $archiveChilds['date'] = true;
                }
            }

            /*
             * Archive posttype
             */
            $postTypes = get_post_types(array('public' => true), 'objects');
            if(!empty($postTypes)) {
                $archivePostTypes = array();
                foreach ($postTypes as $postType) {
                    if($postType->has_archive) {
                        $archivePostTypes[] = $postType->name;
                    }
                }

                if(!empty($archivePostTypes)) {
                    //archive-posttype
                    $archivePostTypeFiles = preg_grep('/^archive-(?!\d).+\.php$/is', $files);
                    if (!empty($archivePostTypeFiles)) {
                        $archivePostTypesFromFiles = array();
                        foreach ($archivePostTypeFiles as $archivePostTypeFile) {
                            $archivePostTypeArr = explode('archive-', $archivePostTypeFile);
                            $archivePostTypeWithExt = explode('.php', end($archivePostTypeArr));
                            $archivePostType = $archivePostTypeWithExt[0];
                            $archivePostTypesFromFiles[] = $archivePostType;

                            if (in_array($archivePostType, $archivePostTypes)) {
                                showOption(get_post_type_archive_link($archivePostType), $archivePostTypeFile, null, false, false, 'Archive ' . $archivePostType);
                            }
                        }
                        $diffArchivePostType = array_diff($archivePostTypes, $archivePostTypesFromFiles);
                        if (!empty($diffArchivePostType)) {
                            $archiveChilds['archive-posttype'] = true;
                        }
                    }
                }
            }

            /*
             * Archive
             */
            foreach($archiveChilds as $key => $value) {
                if($value) {
                    $archiveTemplate = get_archive_template();
                    if(!empty($archiveTemplate)) {
                        $archivePath = explode('/', $archiveTemplate);
                        $archiveFile = end($archivePath);

                        switch($key) {
                            case 'category':
                                $link = get_category_link(key($diffCategory));
                                $entityType = 'cat';
                                $entityId = key($diffCategory);
                                break;
                            case 'tag':
                                $link = get_tag_link(key($diffTag));
                                $entityType = 'tag_id';
                                $entityId = key($diffTag);
                                break;
                            case 'author':
                                $link = get_author_posts_url(key($diffAuthor));
                                $entityType = 'author';
                                $entityId = key($diffAuthor);
                                break;
                            case 'date':
                                $link = get_year_link($lastPostYear);
                                $entityType = 'year';
                                $entityId = $lastPostYear;
                                break;
                            case 'archive-posttype':
                                $link = get_post_type_archive_link($diffArchivePostType[key($diffArchivePostType)]);
                                $entityType = 'post_type';
                                $entityId = $diffArchivePostType[key($diffArchivePostType)];
                                break;
                        }
                        showOption($link, $archiveFile, null, false, false, 'Archive');
                    }
                    break;
                }
            }


            $singleChilds = array('single-post' => false, 'single-posttype' => false);

            /*
             * Single post
             */
            if(!empty($lastPost)) {
                $singlePostFile = $motopressSettings['theme_root'] . '/' . $motopressSettings['current_theme'] . '/single-post.php';
                $singlePostTemplate = (file_exists($singlePostFile)) ? $singlePostFile : '';
                if(!empty($singlePostTemplate)) {
                    $singlePostPath = explode('/', $singlePostTemplate);
                    $singlePostFile = end($singlePostPath);
                    showOption(get_permalink($lastPost->ID), $singlePostFile, null, false, false, 'Single post');
                } else {
                    $singleChilds['single-post'] = true;
                }
            }

            /*
             * Single posttype
             */
            $customPostTypes = get_post_types(array('public' => true, '_builtin' => false), 'objects');
            if (!empty($customPostTypes)) {
                $customPostTypesWithPosts = array();
                foreach ($customPostTypes as $customPostType) {
                    $countPosts = wp_count_posts($customPostType->name);
                    if ($countPosts->publish > 0) {
                        $customPostTypesWithPosts[] = $customPostType->name;
                    }
                }

                if (!empty($customPostTypesWithPosts)) {
                    $singlePostTypeFiles = preg_grep('/^single-(?!post).+\.php$/is', $files);
                    if(!empty($singlePostTypeFiles)) {
                        $singlePostTypesFromFiles = array();
                        foreach($singlePostTypeFiles as $singlePostTypeFile) {
                            $singlePostTypeArr = explode('single-', $singlePostTypeFile);
                            $singlePostTypeWithExt = explode('.php', end($singlePostTypeArr));
                            $singlePostType = $singlePostTypeWithExt[0];

                            $singlePostTypesFromFiles[] = $singlePostType;

                            if(in_array($singlePostType, $customPostTypesWithPosts)) {
                                $post = get_posts(array('numberposts' => 1, 'post_type' => $singlePostType));
                                $post = $post[0];
                                showOption(get_permalink($post->ID), $singlePostTypeFile, null, false, false, 'Single ' . $singlePostType);
                            }
                        }

                        $diffSinglePostType = array_diff($customPostTypesWithPosts, $singlePostTypesFromFiles);
                        if (!empty($diffSinglePostType)) {
                            $post = get_posts(array('numberposts' => 1, 'post_type' => $diffSinglePostType[key($diffSinglePostType)]));
                            $post = $post[0];

                            $singleChilds['single-posttype'] = true;
                        }
                    }
                }
            }

            /*
             * Single
             */
            foreach($singleChilds as $key => $value) {
                if($value) {
                    $singleTemplate = get_single_template();
                    if(!empty($singleTemplate)) {
                        $singlePath = explode('/', $singleTemplate);
                        $singleFile = end($singlePath);

                        switch($key) {
                            case 'single-post':
                                $id = $lastPost->ID;
                                break;
                            case 'single-posttype':
                                $id = $post->ID;
                                break;
                        }
                        showOption(get_permalink($id), $singleFile, null, false, false, 'Single');
                    }
                    break;
                }
            }
        ?>
    </optgroup>
</select>

<?php
function showOption($link = null, $file = null,/*$loopFile = null, $entityType = null,*/ $entityId = null, $hideTemplate = false, $frontPage = false, $name = null) {
    $option = '<option value="' . esc_url($link) . '" data-template="' . $file . '"';
    /*
    if (!is_null($loopFile)) {
        $option .= ' data-motopress-loop-file="' . $loopFile . '"';
    }
    if (!is_null($entityType)) {
        $option .= ' data-motopress-entity-type="' . $entityType . '"';
    }
    */
    if (!is_null($entityId)) {
        $option .= ' data-motopress-entity-id="' . $entityId . '"';
    }
    if ($hideTemplate) {
        $option .= ' data-motopress-hide-template="' . (int)$hideTemplate . '"';
    }
    if ($frontPage) {
        $option .= ' selected';
    }
    $option .= '>'. $name .'</option>';
    echo $option;
}

//unused
function getLoopFile($file) {
    global $motopressSettings;

    $filePath = $motopressSettings['theme_root'] . '/' . $motopressSettings['current_theme'] . '/' . $file;
    if (file_exists($filePath)) {
        $content = file_get_contents($filePath);
        preg_match_all('/data-motopress-wrapper-file=[\'"]wrapper\/([^\'"]+)[\'"]/is', $content, $wrapperMatches);
        unset($content);

        if (!empty($wrapperMatches[1])) {
            $wrapperFiles = $wrapperMatches[1];

            $loopFile = null;
            foreach ($wrapperFiles as $wrapperFile) {
                $wrapperPath = $motopressSettings['theme_wrapper_root'] . '/' . $wrapperFile;
                $wrapperContent = file_get_contents($wrapperPath);
                preg_match('/data-motopress-loop-file=[\'"](loop\/[^\'"]+)[\'"]/is', $wrapperContent, $loopMatches);

                if (!empty($loopMatches[1])) {
                    $loopFile = trim($loopMatches[1]);
                    break;
                } else {
                    preg_match('/\*\s*Default\s+Loop:\s*([^\*]+)\s*\*/is', $wrapperContent, $defaultLoopMatches);
                    if (!empty($defaultLoopMatches[1])) {
                        $loopFile = trim($defaultLoopMatches[1]);
                        break;
                    }
                }

                unset($wrapperContent);
            }
            return $loopFile;
        }
    }
}
