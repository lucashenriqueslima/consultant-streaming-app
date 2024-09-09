<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pages extends Model
{
    use HasFactory;

    public function setUrlAmigavel($url_amigavel) {
		$url = urlAmigavel($url_amigavel);
		$pronto = false;
		while(!$pronto) {
			$query = Pages::query();
			$query->where('url', '=', $url);
			if ($this->id)
				$query->where('id', '!=', $this->id);
			if ($query->count()) {
				$url = urlAmigavel($url_amigavel) . '-' . crazyString(6);
			} else {
				$this->url = $url;
				$pronto = true;
			}
		}
	}
}
