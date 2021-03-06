<?php

/**
 *    Copyright 2015 ppy Pty. Ltd.
 *
 *    This file is part of osu!web. osu!web is distributed with the hope of
 *    attracting more community contributions to the core ecosystem of osu!.
 *
 *    osu!web is free software: you can redistribute it and/or modify
 *    it under the terms of the Affero GNU General Public License as published by
 *    the Free Software Foundation, either version 3 of the License, or
 *    (at your option) any later version.
 *
 *    osu!web is distributed WITHOUT ANY WARRANTY; without even the implied
 *    warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 *    See the GNU Affero General Public License for more details.
 *
 *    You should have received a copy of the GNU Affero General Public License
 *    along with osu!web.  If not, see <http://www.gnu.org/licenses/>.
 */

namespace App\Models\Store;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $connection = 'mysql-store';
    protected $table = 'products';
    protected $primaryKey = 'product_id';

    protected $casts = [
        'base_shipping' => 'real',
        'cost' => 'real',
        'display_order' => 'integer',
        'master_product_id' => 'integer',
        'max_quantity' => 'integer',
        'next_shipping' => 'real',
        'product_id' => 'integer',
        'promoted' => 'boolean',
        'stock' => 'integer',
        'weight' => 'integer',
    ];

    private $images;
    private $types;

    public function masterProduct()
    {
        return $this->belongsTo(self::class, 'master_product_id', 'product_id');
    }

    public function category()
    {
        return $this->hasOne('Category');
    }

    public function inStock($quantity = 1)
    {
        return $this->stock === null || $this->stock >= $quantity;
    }

    public function getHeaderImageAttribute($value)
    {
        if ($this->masterProduct) {
            return $this->masterProduct->header_image;
        } else {
            return $value;
        }
    }

    public function getHeaderDescriptionAttribute($value)
    {
        if ($this->masterProduct) {
            return $this->masterProduct->header_description;
        } else {
            return $value;
        }
    }

    public function getDescriptionAttribute($value)
    {
        if ($this->masterProduct) {
            return $this->masterProduct->description;
        } else {
            return $value;
        }
    }

    public function typeMappings()
    {
        if ($this->masterProduct) {
            return $this->masterProduct->typeMappings();
        } else {
            return json_decode($this->type_mappings_json, true);
        }
    }

    public function images()
    {
        if ($this->masterProduct) {
            return $this->masterProduct->images();
        } else {
            if (!$this->images && $this->images_json) {
                $this->images = json_decode($this->images_json, true);
            }

            return $this->images;
        }
    }

    public function scopeLatest($query)
    {
        return $query
            ->where('master_product_id', null)
            ->with('masterProduct')
            ->orderBy('promoted', 'desc')
            ->orderBy('display_order', 'desc');
    }

    public function types()
    {
        $mappings = $this->typeMappings();
        if ($mappings === null) {
            return;
        }

        if ($this->types !== null) {
            return $this->types;
        }

        $currentMapping = $mappings[strval($this->product_id)];
        $this->types = [];

        foreach ($mappings as $product_id => $mapping) {
            foreach ($mapping as $type => $value) {
                if (!isset($this->types[$type])) {
                    $this->types[$type] = [];
                }
                $mappingDiff = array_diff_assoc($mapping, $currentMapping);
                if ((count($mappingDiff) === 0) || (count($mappingDiff) === 1 && isset($mappingDiff[$type]))) {
                    $this->types[$type][$value] = intval($product_id);
                }
            }
        }

        return $this->types;
    }
}
