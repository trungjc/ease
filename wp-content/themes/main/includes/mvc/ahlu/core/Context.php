<?php

class Context{
	
	//////////////////////////Load Language
	public static function breadCrumbs($func=null){
			echo '<div class="breadcrumbs">';

			echo '<ul>';

		if (!is_home()) {

			echo '<li><a href="';

			echo get_option('home');

			echo '">';

			echo 'Home';

			echo '</a><span>&gt;</span></li>';

			if (is_category() || is_single()) {

				echo '<li>';

				the_category(' </li><li> ');

				if (is_single()) {

					echo '</li><li>';

					the_title();

					echo '</li>';

				}

			} elseif (is_page()) {

				if($post->post_parent){

					$anc = get_post_ancestors( $post->ID );

					 

					foreach ( $anc as $ancestor ) {

						$output = '<li><a href="'.get_permalink($ancestor).'" title="'.get_the_title($ancestor).'">'.get_the_title($ancestor).'</a><span>&gt;</span></li>';

					}

					echo $output;

					echo '<li><strong title="'.$title.'"> '.$title.'</strong></li>';

				} else {

					echo '<li><strong> ';

					echo the_title();

					echo '</strong></li>';

				}

			}

		}

		elseif (is_tag()) {single_tag_title();}

		elseif (is_day()) {echo"<li>Archive for "; the_time('F jS, Y'); echo'</li>';}

		elseif (is_month()) {echo"<li>Archive for "; the_time('F, Y'); echo'</li>';}

		elseif (is_year()) {echo"<li>Archive for "; the_time('Y'); echo'</li>';}

		elseif (is_author()) {echo"<li>Author Archive"; echo'</li>';}

		elseif (isset($_GET['paged']) && !empty($_GET['paged'])) {echo "<li>Blog Archives"; echo'</li>';}

		elseif (is_search()) {echo"<li>Search Results"; echo'</li>';}

		echo '</ul>';

		echo '</div>';
	}
	
	///////////
	 /**
	 * truncateHtml can truncate a string up to a number of characters while preserving whole words and HTML tags
	 *
	 * @param string $text String to truncate.
	 * @param integer $length Length of returned string, including ellipsis.
	 * @param string $ending Ending to be appended to the trimmed string.
	 * @param boolean $exact If false, $text will not be cut mid-word
	 * @param boolean $considerHtml If true, HTML tags would be handled correctly
	 *
	 * @return string Trimmed string.
	 */
	public static function truncateHtml($text, $length = 100, $ending = '...', $exact = false, $considerHtml = true) {
		if ($considerHtml) {
			// if the plain text is shorter than the maximum length, return the whole text
			if (strlen(preg_replace('/<.*?>/', '', $text)) <= $length) {
				return $text;
			}
			// splits all html-tags to scanable lines
			preg_match_all('/(<.+?>)?([^<>]*)/s', $text, $lines, PREG_SET_ORDER);
			$total_length = strlen($ending);
			$open_tags = array();
			$truncate = '';
			foreach ($lines as $line_matchings) {
				// if there is any html-tag in this line, handle it and add it (uncounted) to the output
				if (!empty($line_matchings[1])) {
					// if it's an "empty element" with or without xhtml-conform closing slash
					if (preg_match('/^<(\s*.+?\/\s*|\s*(img|br|input|hr|area|base|basefont|col|frame|isindex|link|meta|param)(\s.+?)?)>$/is', $line_matchings[1])) {
						// do nothing
					// if tag is a closing tag
					} else if (preg_match('/^<\s*\/([^\s]+?)\s*>$/s', $line_matchings[1], $tag_matchings)) {
						// delete tag from $open_tags list
						$pos = array_search($tag_matchings[1], $open_tags);
						if ($pos !== false) {
						unset($open_tags[$pos]);
						}
					// if tag is an opening tag
					} else if (preg_match('/^<\s*([^\s>!]+).*?>$/s', $line_matchings[1], $tag_matchings)) {
						// add tag to the beginning of $open_tags list
						array_unshift($open_tags, strtolower($tag_matchings[1]));
					}
					// add html-tag to $truncate'd text
					$truncate .= $line_matchings[1];
				}
				// calculate the length of the plain text part of the line; handle entities as one character
				$content_length = strlen(preg_replace('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', ' ', $line_matchings[2]));
				if ($total_length+$content_length> $length) {
					// the number of characters which are left
					$left = $length - $total_length;
					$entities_length = 0;
					// search for html entities
					if (preg_match_all('/&[0-9a-z]{2,8};|&#[0-9]{1,7};|[0-9a-f]{1,6};/i', $line_matchings[2], $entities, PREG_OFFSET_CAPTURE)) {
						// calculate the real length of all entities in the legal range
						foreach ($entities[0] as $entity) {
							if ($entity[1]+1-$entities_length <= $left) {
								$left--;
								$entities_length += strlen($entity[0]);
							} else {
								// no more characters left
								break;
							}
						}
					}
					$truncate .= substr($line_matchings[2], 0, $left+$entities_length);
					// maximum lenght is reached, so get off the loop
					break;
				} else {
					$truncate .= $line_matchings[2];
					$total_length += $content_length;
				}
				// if the maximum length is reached, get off the loop
				if($total_length>= $length) {
					break;
				}
			}
		} else {
			if (strlen($text) <= $length) {
				return $text;
			} else {
				$truncate = substr($text, 0, $length - strlen($ending));
			}
		}
		// if the words shouldn't be cut in the middle...
		if (!$exact) {
			// ...search the last occurance of a space...
			$spacepos = strrpos($truncate, ' ');
			if (isset($spacepos)) {
				// ...and cut the text in this position
				$truncate = substr($truncate, 0, $spacepos);
			}
		}
		// add the defined ending to the text
		$truncate .= $ending;
		if($considerHtml) {
			// close all unclosed html-tags
			foreach ($open_tags as $tag) {
				$truncate .= '</' . $tag . '>';
			}
		}
		return $truncate;
	}
	
	public static function truncateWords($text, $limit, $ellipsis = '...') {
		$text = strip_tags($text);
		$words = preg_split("/[\n\r\t ]+/", $text, $limit + 1, PREG_SPLIT_NO_EMPTY|PREG_SPLIT_OFFSET_CAPTURE);
		if (count($words) > $limit) {
			end($words); //ignore last element since it contains the rest of the string
			$last_word = prev($words);
			   
			$text =  substr($text, 0, $last_word[1] + strlen($last_word[0])) . $ellipsis;
		}
		return $text;
	}
}
?>