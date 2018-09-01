<?php
/**
 * Utility functions
 *
 */
class HordeWeb_Utils
{
    /**
     * Get a random "we are awesome" quote.
     *
     */
    static public function getQuote()
    {
        include $GLOBALS['fs_base'] . '/config/quotes.php';
        return $quotes[rand(0, count($quotes) - 1)];
    }

    /**
     * Returns a PDO handle on the versions database.
     *
     * @return PDO
     */
    static public function getVersionDb()
    {
        return new PDO('sqlite:' . $GLOBALS['fs_base'] . '/config/versions.sqlite');
    }

    /**
     * Get list of released stable applications.
     *
     * @param string $app  Only return information for this application.
     *
     * @return array
     */
    static public function getStableApps($app = null)
    {
        $query = 'SELECT * FROM versions WHERE state = ?';
        $values = array('stable');
        if ($app) {
            $query .= ' AND application = ?';
            $values[] = $app;
        }

        $stmt = self::getVersionDb()
            ->prepare($query);

        if ($stmt->execute($values)) {
            return $app
                ? $stmt->fetch(PDO::FETCH_ASSOC)
                : $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }

    /**
     * Get list of Horde 4 released applications.
     *
     * @param string $app  Only return information for this application.
     *
     * @return array
     */
    static public function getH4Apps($app = null)
    {
        $query = 'SELECT * FROM versions WHERE pear = ?';
        $values = array(true);
        if ($app) {
            $query .= ' AND application = ?';
            $values[] = $app;
        }

        $stmt = self::getVersionDb()
            ->prepare($query);

        if ($stmt->execute($values)) {
            return $app
                ? $stmt->fetch(PDO::FETCH_ASSOC)
                : $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }

    /**
     * Get list of Horde 3 released applications.
     *
     * @param string $app  Only return information for this application.
     *
     * @return array
     */
    static public function getH3Apps($app = null)
    {
        $query = 'SELECT * FROM versions WHERE state = ?';
        $values = array('three');
        if ($app) {
            $query .= ' AND application = ?';
            $values[] = $app;
        }

        $stmt = self::getVersionDb()
            ->prepare($query);

        if ($stmt->execute($values)) {
            return $app
                ? $stmt->fetch(PDO::FETCH_ASSOC)
                : $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }

    /**
     * Get list of applications in development.
     *
     * @param string $app  Only return information for this application.
     *
     * @return array
     */
    static public function getDevApps($app = null)
    {
        $query = 'SELECT * FROM versions WHERE state = ?';
        $values = array('dev');
        if ($app) {
            $query .= ' AND application = ?';
            $values[] = $app;
        }

        $stmt = self::getVersionDb()
            ->prepare($query);

        if ($stmt->execute($values)) {
            return $app
                ? $stmt->fetch(PDO::FETCH_ASSOC)
                : $stmt->fetchAll(PDO::FETCH_ASSOC);
        }
    }

    static public function downloadIcon($view, $app)
    {
        return '<a href="' . $view->urlFor(array('controller' => 'download', 'action' => 'app', 'app' => $app)) . '"><img src="' . $GLOBALS['host_base'] . '/images/download.gif" class="download" alt="Download page" title="Download page" /></a>';
    }

    static public function app_download_link($key, $elt, $graphic = false, $controller = null)
    {
        $text = $elt['name'] . ' ' . $elt['version'];
        if ($graphic) {
            $text = '<img class="download" src="' . $GLOBALS['host_base'] . '/images/download.gif" alt="' . $text . '" />';
        }

        return '<a href="' . self::app_download_url($key, $elt, $controller) . '">' . $text . '</a> (' . $elt['date'] . ')';
    }

    static public function app_download_url($key, $elt, $controller = null)
    {
        if (!empty($elt['pear'])) {
            if ($controller) {
                return $controller->getView()->urlWriter->urlFor('app', array('app' => $key, 'action' => 'docs')) . '/INSTALL';
            } else {
                return 'http://pear.horde.org/';
            }
        }
        $dir = isset($elt['dir']) ? $elt['dir'] : $key;
        return 'ftp://ftp.horde.org/pub/' .
            rtrim($dir, ' /') .
            '/' .
            (isset($elt['file']) ? $elt['file'] : $dir . '-latest.tar.gz');
    }

    static public function app_patches_url($key, $elt)
    {
        return 'ftp://ftp.horde.org/pub/' .
            rtrim(isset($elt['dir']) ? $elt['dir'] : $key, ' /') .
            '/patches/';
    }


    /**
     * Generate a linked thumbnail for screenshots.
     *
     * @param string $app        The application the screenshot is for
     * @param string $imagename  The image name
     * @param boolean $png       Full size screenshot is in PNG format.
     * @return type
     */
    static public function ssLink($app, $imagename, $png = false, $text = '')
    {
        $full = $GLOBALS['host_base'] . '/images/screenshots/' . $app . '/' . $imagename . ($png ? '.png' : '.jpg');
        $thumb = $GLOBALS['host_base'] . '/images/screenshots/' . $app . '/' . $imagename . '-thumb.jpg';
        $s = "<a class=\"lightbox\" href=\"$full\"><img border=\"0\" ";
        $s .= "src=\"$thumb\" alt=\"$imagename\" /></a>";

        if (!empty($text)) {
            $s .= '<br />' . $text;
        }

        return $s;
    }

    /**
     * Generates an img tag for a logo
     *
     * @param string $logo  The logo
     *
     * @return string  The img tag
     */
    static public function logoImg($logo)
    {
        return '<img src="' . $GLOBALS['host_base'] . '/images/logos/' . $logo . '" alt="' . $logo . '" />';
    }

    static public function breadcrumbs($controller, $params = array())
    {
        $separator = '&nbsp;&nbsp;&raquo;&nbsp;&nbsp;';
        $view = $controller->getView();
        switch (get_class($controller)) {
        case 'HordeWeb_App_Controller':
            $crumb = $view->linkToUnlessCurrent('Community', array('controller' => 'community'))
                . $separator
                . $view->linkToUnlessCurrent('Applications', array('controller' => 'app'));

                if (!empty($view->appname)) {
                    $crumb .= $separator;
                }
                $crumb .= $view->linkToUnless(empty($view->appname) || !$view->isCurrentPage(array('controller' => 'app')), $view->appnameHuman, array('controller' => 'app', 'action' => 'app'));
            break;
        case 'HordeWeb_Library_Controller':
            $crumb = $view->linkToUnlessCurrent('Development', array('controller' => 'development'))
                . $separator
                . $view->linkToUnlessCurrent('Libraries', array('controller' => 'library'));

                if (!empty($view->shortLibraryName)) {
                    $crumb .= $separator;
                }
                $crumb .= $view->linkToUnless(empty($view->shortLibraryName) || !$view->isCurrentPage(array('controller' => 'library')), $view->shortLibraryName, array('controller' => 'library', 'library' => $view->libraryName, 'action' => 'library'));
            break;
        case 'HordeWeb_Community_Controller':
            $crumb = $view->linkToUnlessCurrent('Community', array('controller' => 'community'));
            if (!empty($params)) {
                foreach ($params as $name => $action) {
                    $crumb .= $separator . $view->linkToUnlessCurrent($name, array('controller' => 'community', 'action' => $action));
                }
            }
            break;
        case 'HordeWeb_Development_Controller':
            $crumb = $view->linkToUnlessCurrent('Development', array('controller' => 'development'));
            if (!empty($params)) {
                foreach ($params as $name => $action) {
                    $crumb .= $separator . $view->linkToUnlessCurrent($name, array('controller' => 'development', 'action' => $action));
                }
            }
            break;
        case 'HordeWeb_Licenses_Controller':
            $crumb = $view->linkToUnlessCurrent('Licenses', array('controller' => 'licenses'));
            if (!empty($params)) {
                foreach ($params as $name => $action) {
                    $crumb .= $separator . $view->linkToUnlessCurrent($name, array('controller' => 'licenses', 'action' => $action));
                }
            }
        }

        return $crumb;
    }

    static public function fimg($country)
    {
        if (file_exists($GLOBALS['fs_base'] . '/images/flags/' . $country . '.gif')) {
            echo '<img align="middle" src="' . $GLOBALS['host_base'] . '/images/flags/' . $country . '.gif" border="0" alt="' . strtoupper($country) . '" width="18" height="12" />&nbsp;&nbsp;';
        } else {
            echo '<img align="middle" src="'. $GLOBALS['host_base'] . '/images/blank.gif" border="0" alt="" width="18" height="12" />&nbsp;&nbsp;';
        }
    }

    static public function getLists()
    {
        require $GLOBALS['fs_base'] . '/config/lists.php';
        return $lists;
    }

    static public function getLibraries()
    {
        return $GLOBALS['injector']->getInstance('HordeWeb_Utils_Libraries');
    }

    /**
     * @TODO: Remove core - configure via config values from config/
     */
    static public function getCache()
    {
        return $GLOBALS['injector']->getInstance('Horde_Cache');
    }

    /**
     * Replace links with links to the web.horde.ws subdomain.
     *
     * Replaces any URLs that do not point to a horde.org domain.
     *
     * @param  string  The URL to replace.
     *
     * @return string  The new URL.
     */
    static public function wrapLink($link)
    {
        // $parts = parse_url($link);
        // if (!empty($parts['scheme']) &&
        //     strpos($parts['scheme'], 'http') === 0 &&
        //     strpos($parts['host'], 'horde.org') === false) {
        //     $link = 'https://'
        //         . preg_replace('/^www\./', '', $parts['host'])
        //         . (!empty($parts['path']) ? $parts['path'] : '');
        // }
        return $link;
    }

}
