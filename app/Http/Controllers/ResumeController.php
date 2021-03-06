<?php

namespace App\Http\Controllers;

use App\Models\Good;
use App\Models\Product;
use App\Models\TaskLog;
use Carbon\Carbon;
use GuzzleHttp\Client;
use GuzzleHttp\Promise;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Http\Request;

class ResumeController extends Controller
{
    /**
     * 最新履歷及查詢結果頁面
     *
     * @param Request $request
     * @return void
     */
    public function index(Request $request)
    {
        $products = Product::withTranslations();

        if ($request->farm) {
            $products = $products->whereTranslation('farm', urldecode($request->farm));
        }

        if ($request->product) {
            $products = $products->where('product_id', $request->product);
        }

        if ($request->product_name) {
            $products = $products->whereTranslation('product_name', urldecode($request->product));
        }

        if ($request->goods) {
            $products = $products->whereHas('goods', function (Builder $query) use ($request) {
                $query->where('product_goods.goods_id', $request->goods);
            });
        }

        if ($request->enable) {
            $products = $products->where('website-enable', $request->enable);
        }

        $products = $products->get();

        $logs = TaskLog::with(['product', 'translations'])->whereIn('product_id', $products->pluck('product_id'))->orderby('timestamp', 'desc');
        if (empty($request->query())) {
            $logs = $logs->limit(3);
        }
        $logs = $logs->get();
        $products = $logs->pluck('product')->unique(function ($item) {
            return $item['product_id'];
        });
        $products = $products->translate(app()->getLocale());
        $dates = [];
        foreach ($logs as $l) {
            $date = [];
            $date['date'] = Carbon::createFromTimestamp($l->timestamp)->format('Y-m-d');
            $l->scrollId = $date['date'];
            $date['badge'] = true;
            $dates[] = $date;
        }
        if (!empty($dates)) {
            $arr = explode("-", $dates[0]['date']);
            $year = $arr[0];
            $month = $arr[1];
        } else {
            $year = $month = null;
            $dates[] = ['date' => null];
        }
        $logs = $logs->translate(app()->getLocale());
        return view('resumes.index', compact(['logs', 'dates', 'year', 'month', 'products']));
    }

    /**
     * 查詢履歷頁面
     *
     * @return void
     */
    public function inquiry()
    {
        $farms = Good::withTranslations()
            ->distinct('farm')
            ->orderby('farm')
            ->where('website-enable', 1)
            ->get()
            ->translate(app()->getLocale())
            ->pluck('farm', 'farm');
        return view('resumes.inquiry', compact(['farms']));
    }

    /**
     * 取得 product_name
     *
     * @param Request $request
     * @return void
     */
    public function good(Request $request)
    {
        $request->validate([
            'farm' => 'required',
        ]);
        return Good::withTranslations()
            ->distinct("goods_name")
            ->whereTranslation('farm', urldecode($request->farm))
            ->where('website-enable', 1)
            ->get()
            ->translate(app()->getLocale())
            ->pluck('goods_name', 'goods_id');
    }

    /**
     * 取得 product_name
     *
     * @param Request $request
     * @return void
     */
    public function product(Request $request)
    {
        $request->validate([
            'id' => 'required',
            'type' => 'required',
        ]);
        switch ($request->type) {
            case 'good':
                return Good::withTranslations()
                    ->where('goods_id', $request->id)
                // ->where('website-enable', 1)
                    ->firstOrFail()
                    ->translate(app()->getLocale());
            case 'product':
                return Product::withTranslations()
                    ->where('product_id', $request->id)
                // ->where('website-enable', 1)
                    ->firstOrFail()
                    ->translate(app()->getLocale());
            default:
                return [];
        }
    }
    /**
     * 取得驗証資料
     *
     * @param Request $request
     * @return void
     */
    public function validation(Request $request)
    {
        $request->validate([
            'products' => 'required',
        ]);
        $promises = [];
        $client = new Client([
            'base_uri' => env('VALID_API_URL'),
        ]);
        foreach ($request->products as $id) {
            $promise = $client->postAsync('api/check_by_product_id', [
                'form_params' => [
                    'product_id' => $id,
                ],
                'verify' => false,
            ]);
            $promises[] = $promise;
        }
        $results = Promise\unwrap($promises);
        $response = [];
        foreach ($results as $res) {
            if ($res->getStatusCode() != 200) {
                continue;
            }
            $logs = json_decode($res->getBody(), true);
            foreach ($logs as $log) {
                $response[$log['log_id']] = $log['result'];
            }
        }
        return $response;
    }
}
