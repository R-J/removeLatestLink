<?php

$PluginInfo['removeLatestLink'] = [
    'Name' => 'Remove "#latest" link',
    'Description' => 'Removes the suffix "#latest" from every link by replacing Vanillas anchor() function.',
    'Version' => '0.1',
    'RequiredApplications' => ['Vanilla' => '>= 2.2'],
    'RequiredPlugins' => false,
    'RequiredTheme' => false,
    'MobileFriendly' => true,
    'Author' => 'Robin Jurinka',
    'AuthorUrl' => 'http://vanillaforums.org/profile/r_j',
    'License' => 'MIT'
];

class RemoveLatestLinkPlugin extends Gdn_Plugin {
}

/**
 * Writes an anchor tag.
 *
 * This is a copy of Vanillas anchor() function but with the addition that
 * it strips "#latest" from $Destination.
 */
if (!function_exists('anchor')) {
    /**
     * Builds and returns an anchor tag.
     */
    function anchor($Text, $Destination = '', $CssClass = '', $Attributes = array(), $ForceAnchor = false) {
        if ($Destination == '' && $ForceAnchor === false) {
            return $Text;
        }

        if (!is_array($CssClass) && $CssClass != '') {
            $CssClass = array('class' => $CssClass);
        }

        if (!is_array($Attributes)) {
            $Attributes = array();
        }

        $SSL = null;
        if (isset($Attributes['SSL'])) {
            $SSL = $Attributes['SSL'];
            unset($Attributes['SSL']);
        }

        $WithDomain = false;
        if (isset($Attributes['WithDomain'])) {
            $WithDomain = $Attributes['WithDomain'];
            unset($Attributes['WithDomain']);
        }

        $Prefix = substr($Destination, 0, 7);
        if (!in_array($Prefix, array('https:/', 'http://', 'mailto:')) && ($Destination != '' || $ForceAnchor === false)) {
            $Destination = Gdn::Request()->Url($Destination, $WithDomain, $SSL);
        }

        if (strtolower(substr($Destination, -7)) === '#latest') {
            $Destination = substr($Destination, 0, -7);
        }
        return '<a href="'.htmlspecialchars($Destination, ENT_COMPAT, C('Garden.Charset', 'UTF-8')).'"'.Attribute($CssClass).Attribute($Attributes).'>'.$Text.'</a>';
    }
}
