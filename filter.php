<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * Filter for generating collapsible regions easily
 *
 * @package    filter_collapsible
 * @copyright  2015 Jan Eberhardt <eberhardt@math.tu-berlin.de>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

/**
 * Collapsible region filter
 *
 * @author Jan Eberhardt <eberhardt@math.tu-berlin.de>
 */
class filter_collapsible extends moodle_text_filter {

    private $started;

    const TAG = '{collapsible}';

    /**
     * Apply the filter to the text
     *
     * @see filter_manager::apply_filter_chain()
     * @param string $text to be processed by the text
     * @param array $options filter options
     * @return string text after processing
     */
    public function filter($text, array $options = array()) {
        if (strpos($text, self::TAG) !== false) {
            $this->replace_collapsible($text);
        }

        return $text;
    }

    /**
     * Replace tags with actual HTML.
     *
     * @param string $text
     */
    private function replace_collapsible(&$text) {
        static $id = 0;

        $started = false;
        $replaced = '';
        $parts = explode(self::TAG, $text);
        $lead = array_shift($parts);
        foreach ($parts as $tail) {
            if ($started) {
                $started = false;
                $replaced .= $this->end_region($tail);
            } else {
                $started = true;
                if (preg_match('/^\[([^\]]+)\]([\s\S]*)/', $tail, $match)) {
                    $more = $match[1];
                    $tail = $match[2];
                } else {
                    $more = get_string("clicktohideshow");
                }
                $replaced .= $this->start_region('collapsible-' . $id++, $more, $tail);;
            }
        }

        if ($started) {
            $replaced .= $this->end_region(); // Fix, if end tag was forgotten.
        }

        $text = $lead . "\n" .  $replaced;
    }

    /**
     * Returns HTML of a collapsible region (start) with beginning content.
     *
     * @param string $id
     * @param string $more
     * @param string $content
     * @return string
     */
    public function start_region($id, $more, $content = '') {
        return "\n" . print_collapsible_region_start('collapsablefiltered', $id, $more, false, true, true) . "\n" . $content;
    }

    /**
     * Returns HTML of the end of collapsible region with trailing content.
     *
     * @param string $content_after
     * @return string
     */
    public function end_region($contentafter = '') {
        return "\n" . print_collapsible_region_end(true) . "\n". $contentafter;
    }
}
