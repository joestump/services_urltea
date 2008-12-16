<?php

/* vim: set expandtab tabstop=4 shiftwidth=4 softtabstop=4: */

/**
 * An interface for urlTea
 *
 * PHP version 5.1.0+
 *
 * Copyright (c) 2007, The PEAR Group
 * 
 * All rights reserved.
 * 
 * Redistribution and use in source and binary forms, with or without 
 * modification, are permitted provided that the following conditions are met:
 *
 *  - Redistributions of source code must retain the above copyright notice,
 *    this list of conditions and the following disclaimer.
 *  - Redistributions in binary form must reproduce the above copyright notice,
 *    this list of conditions and the following disclaimer in the documentation
 *    and/or other materials provided with the distribution.
 *  - Neither the name of the The PEAR Group nor the names of its contributors 
 *    may be used to endorse or promote products derived from this software 
 *    without specific prior written permission.
 *
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" 
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, THE 
 * IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR PURPOSE 
 * ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR CONTRIBUTORS BE 
 * LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, EXEMPLARY, OR 
 * CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, PROCUREMENT OF 
 * SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; OR BUSINESS 
 * INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, WHETHER IN 
 * CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE OR OTHERWISE) 
 * ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, EVEN IF ADVISED OF THE 
 * POSSIBILITY OF SUCH DAMAGE.
 *
 * @category    Services
 * @package     Services_urlTea
 * @author      Joe Stump <joe@joestump.net> 
 * @copyright   1997-2007 The PHP Group
 * @license     http://www.opensource.org/licenses/bsd-license.php 
 * @version     CVS: $Id:$
 * @link        http://pear.php.net/package/Services_urlTea
 * @link        http://urltea.com
 */

require_once 'Services/urlTea/Exception.php';

/**
 * Services_urlTea
 *
 * <code>
 * <?php
 * require_once 'Services/urlTea.php';
 * try {
 *     $urlTea = new Services_urlTea();
 *     $url = $urlTea->create('http://www.joestump.net');
 *     echo $url;
 * } catch (Services_urlTea_Exception $e) {
 *     echo $e->getMessage(); 
 * }
 * ?>
 * </code>
 *
 * @category    Services
 * @package     Services_urlTea
 * @author      Joe Stump <joe@joestump.net> 
 * @link        http://urltea.com
 */
class Services_urlTea
{
    /**
     * Location of urlTea API
     *
     * @var         string      $api            URL of urlTea API
     * @static
     */
    protected $api = 'http://urltea.com/api/text/';

    /**
     * Create a urlTea
     *
     * @access      public
     * @param       string      $destination    The URL to make into tea
     * @return      string
     * @static 
     */
    public function create($destination)
    {
        $ch = curl_init();

        $uri = $this->api . '?url=' . $destination;
        curl_setopt($ch, CURLOPT_URL, $uri);
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Services_urlTea @version@');
        $result = curl_exec($ch);
        if ($result === false) {
            throw new Services_urlTea_Exception(curl_error($ch), curl_errno($ch));
        }
        curl_close($ch);

        if (!preg_match('/^http:\/\/urltea.com\/[a-z0-9]+$/i', $result)) {
            throw new Services_urlTea_Exception('An error occurred while creating ' . $destination);
        }

        return $result;
    }

    /**
     * Do a reverse lookup of a urlTea
     *
     * @access      public
     * @param       string      $url        urlTea URL to look up
     * @return      string      The destination URL of the urlTea URL
     * @static
     */
    public function lookup($url) 
    {
        if (!preg_match('/^http:\/\/urltea.com\/[a-z0-9]+$/i', $url)) {
            throw new Services_urlTea_Exception('Invalid urlTea URL ' . $url);
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);        
        curl_setopt($ch, CURLOPT_HEADER, true);
        curl_setopt($ch, CURLOPT_NOBODY, true);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Services_urlTea @version@');
        $result = curl_exec($ch);
        curl_close($ch);

        $m = array();
        if (preg_match("/Location: (.*)\n/", $result, $m)) {
            if (isset($m[1]) && preg_match('/^https?:\/\//i', $m[1])) {
                return trim($m[1]);
            }
        }

        throw new Services_urlTea_Exception('No redirection found');
    }
}

?>
