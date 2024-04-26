<?php

namespace App\Models;

use DB;
use Illuminate\Database\Eloquent\Model;

class _Administrator extends Model
{
    public static function getGTCFeaturedNews($data)
    {
        if ($data['is_featured'] > 0) {
            return DB::connection('mysql2')->table('gtc_news')
                ->orderBy('id', 'desc')
                ->where('is_featured', $data['is_featured'])
                ->where('status', 1)
                ->get();
        }

        return DB::connection('mysql2')->table('gtc_news')
            ->where('is_featured', 0)
            ->where('status', 1)
            ->orderBy('id', 'desc')
            ->limit($data['limit'])
            ->get();
    }

    public static function getGTCFeaturedNewsMore($data)
    {
        return DB::table('gtc_news')
            ->where('is_featured', $data['is_featured'])
            ->where('status', 1)
            ->orderBy('id', 'desc')
            ->where('id', '<', $data['lastid'])
            ->limit($data['limit'])
            ->get();
    }

    public static function getGTCDialogFeatured($data)
    {
        return DB::connection('mysql2')->table('gtc_news')
            ->orderBy('id', 'desc')
            ->where('is_featured', 2)
            ->where('status', 1)
            ->limit($data['limit'])
            ->get();
    }

    public static function getGTCDialogList($data)
    {
        return DB::connection('mysql2')->table('gtc_news')
            ->orderBy('id', 'desc')
            ->where('is_featured', 2)
            ->where('status', 1)
            ->get();
    }
}
