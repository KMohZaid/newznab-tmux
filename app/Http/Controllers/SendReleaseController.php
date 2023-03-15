<?php

namespace App\Http\Controllers;

use App\Models\User;
use Blacklight\CouchPotato;
use Blacklight\NZBGet;
use Blacklight\SABnzbd;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SendReleaseController extends BasePageController
{
    /**
     * @throws \Exception
     */
    public function couchPotato(Request $request): void
    {
        $this->setPreferences();
        if (empty($request->input('id'))) {
            $this->show404();
        } else {
            $cp = new CouchPotato($this);

            if (empty($cp->cpurl)) {
                $this->show404();
            }

            if (empty($cp->cpapi)) {
                $this->show404();
            }
            $id = $request->input('id');
            $cp->sendToCouchPotato($id);
        }
    }

    /**
     * @throws \Exception
     */
    public function sabNzbd(Request $request)
    {
        $this->setPreferences();
        if (empty($request->input('id'))) {
            $this->show404();
        }

        $sab = new SABnzbd($this);

        if (empty($sab->url)) {
            $this->show404();
        }

        if (empty($sab->apikey)) {
            $this->show404();
        }

        $guid = $request->input('id');

        $sab->sendToSab($guid);
    }

    /**
     * @throws \Exception
     */
    public function nzbGet(Request $request): void
    {
        $this->setPreferences();
        if (empty($request->input('id'))) {
            $this->show404();
        }

        $nzbget = new NZBGet($this);

        if (empty($nzbget->url)) {
            $this->show404();
        }

        if (empty($nzbget->username)) {
            $this->show404();
        }

        if (empty($nzbget->password)) {
            $this->show404();
        }

        $guid = $request->input('id');

        $nzbget->sendURLToNZBGet($guid);
    }

    /**
     * @throws \Exception
     */
    public function queue(Request $request): void
    {
        $this->setPreferences();
        if (empty($request->input('id'))) {
            $this->show404();
        }

        $user = $this->userdata;
        if ((int) $this->userdata->queuetype !== 2) {
            $sab = new SABnzbd($this);
            if (empty($sab->url)) {
                $this->show404();
            }
            if (empty($sab->apikey)) {
                $this->show404();
            }
            $sab->sendToSab($request->input('id'));
        } elseif ((int) $user['queuetype'] === 2) {
            $nzbget = new NZBGet($this);
            $nzbget->sendURLToNZBGet($request->input('id'));
        }
    }
}
