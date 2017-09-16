<?php
namespace app\Http\Controllers\Technicals;

class TechnicalController
{
    public function getMA(int $term, array $c_prices)
    {
        //TODO:termとc_pricesの要素数が同じかどうか調べるバリデータ

        $sum = 0;
        for ($count=0;$count<$term;$count++){
            $sum += $c_prices;
        }
            return $sum / $term;
    }

    public function getBBAll($term, $c_prices)
    {
        //TODO:termとc_pricesの要素数が同じかどうか調べるバリデータ

        //「(期間×価格の2乗の合計-価格の合計の2乗)/期間×(期間-1)」の平方根
        // → a÷bの平方根
        $sum_price_square = 0; //価格の2乗の合計
        $price_sum_square = 0; //価格の合計の2乗
        foreach ($c_prices as $c_price){
            $sum_price_square += ($c_price ** 2);
            $price_sum_square += $c_price;
            //TODO:termとpriceの数が違っていたらエラー
        }
        $price_sum_square = $price_sum_square ** 2;
        $a = $term * $sum_price_square - $price_sum_square;
        $b = $term * ($term - 1);

        $sd = sqrt($a / $b); //標準偏差
        $ma = $this->getMA($term, $c_prices); //センターライン(平均線)

        //戻り値：-3σ, -2σ, -1σ, MA, +1σ, +2σ, +3σの配列
        return ['minus3' => $ma-($sd*3),
                'minus2' => $ma-($sd*2),
                'minus1' => $ma-$sd,
                'ma' => $ma,
                'plus1' => $ma+$sd,
                'plus2' => $ma+($sd*2),
                'plus3' => $ma+($sd*3),
                ];
    }

    public function getIchimoku($c_prices)
    {
        //TODO: 配列の順番を後ろからに入れ替える。
        //TODO: c_pricesの要素数が52かどうか調べるバリデータ

        //転換線(転換値) = (直近9日間の最高値＋最安値)÷2(※9日間の中心値)
        $tenkan_prices[] = $this->getIchimokuLocal(9, $c_prices);
        $tenkan = ($tenkan_prices[$max_price]+$tenkan_prices[$min_price])/2;

        //基準線(基準値) = (直近26日間の最高値＋最安値)÷2(※26日間の中心値)
        $kijun_prices[] = $this->getIchimokuLocal(26, $c_prices);
        $kijun = ($kijun_prices[$max_price]+$kijun_prices[$min_price])/2;

        //先行スパン1 = (転換値＋基準値)÷2 ⇒26日先に記入
        $senkou1 = ($tenkan+$kijun)/2;

        //先行スパン2 = (直近52日間の最高値＋最安値)÷2 ⇒26日先に記入
        $senkou2_prices[] = $this->getIchimokuLocal(52, $c_prices);
        $senkou2 = ($senkou2_prices[$max_price]+$senkou2_prices[$min_price])/2;

        //遅行スパン = (遅行線)当日の終値を26日前に記入
        //配列は直近のデータから順番になっているため、当日は要素ゼロでOK。
        $chikou = $c_prices[0];

    }

    private function getIchimokuLocal($term, $c_prices){
        $min_price = 999999;
        $max_price = 0;
        for ($count=0; $count<$term; $count++){
            if ($min_price > $c_prices[$count]){$min_price = $c_prices[$count];}
            if ($max_price < $c_prices[$count]){$max_price = $c_prices[$count];}
        }
        return ['min_price' => $min_price,
                'max_price' => $max_price,
                ];
    }


}
