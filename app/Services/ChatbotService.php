<?php

namespace App\Services;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class ChatbotService
{
    /**
     * Tìm tour phù hợp dựa trên input của người dùng.
     * Hoặc trả về danh sách tất cả tour nếu được yêu cầu.
     *
     * @param string $userInput
     * @return array|object|null Trả về một mảng chứa dữ liệu (text, tour_id, tours_list, quick_replies)
     * hoặc một object tour (nếu tìm thấy 1 tour cụ thể), hoặc null.
     */
    public function findMatchingTour(string $userInput)
    {
        $normalizedUserInput = trim(mb_strtolower($this->removeVietnameseAccents($userInput), 'UTF-8'));

        // --- Logic xử lý yêu cầu GỬI YÊU CẦU GỌI LẠI ---
        if (str_contains($normalizedUserInput, 'gui yeu cau goi lai') ||
            str_contains($normalizedUserInput, 'muon duoc goi lai') ||
            str_contains($normalizedUserInput, 'noi chuyen voi nhan vien') ||
            str_contains($normalizedUserInput, 'can tu van them')) {

            Log::info("User requested a callback.");
            return [
                'text' => "Vui lòng điền thông tin của bạn vào biểu mẫu bên dưới, chúng tôi sẽ liên hệ lại sớm nhất có thể!",
                'request_callback_form' => true,
                'quick_replies' => [
                   
                ]
            ];
        }

        // --- Logic ưu tiên: Xử lý yêu cầu "tất cả tour hiện có" ---
        if (str_contains($normalizedUserInput, 'tat ca tour hien co') ||
            str_contains($normalizedUserInput, 'danh sach tour') ||
            str_contains($normalizedUserInput, 'show tour') ||
            str_contains($normalizedUserInput, 'xem tat ca tour')) {
            $allTours = DB::table('available_tours')->get();
            if ($allTours->isEmpty()) {
                return [
                    'text' => "Hiện tại chưa có tour nào. Vui lòng quay lại sau nhé!",
                    'tours_list' => [],
                    'quick_replies' => [
                        ['text' => 'Liên hệ tư vấn', 'value' => 'tôi muốn nói chuyện với nhân viên']
                    ]
                ];
            } else {
                $quickReplies = [];
                $count = 0;
                foreach ($allTours as $tour) {
                    if ($count < 5) {
                        $quickReplies[] = ['text' => $tour->name_tour, 'value' => $tour->name_tour];
                        $count++;
                    } else {
                        break;
                    }
                }
                $quickReplies[] = ['text' => 'Liên hệ tư vấn', 'value' => 'tôi muốn nói chuyện với nhân viên'];

                return [
                    'text' => "Dưới đây là một số tour hiện có:",
                    'tours_list' => $allTours->toArray(),
                   'quick_replies' => $quickReplies
                ];
            }
        }

        // --- Logic tìm kiếm tour cụ thể ---
        $tour = null;
        $tour = $this->findTourByName($userInput);
        if (!$tour) {
            $tour = $this->findTourByPrice($userInput);
        }
        if (!$tour) {
            $tour = $this->findTourByLocation($userInput);
        }
        
        // Thêm một điều kiện tìm kiếm chung nếu các hàm trên không tìm thấy
        if (!$tour && strlen($userInput) > 2) {
            $tour = DB::table('available_tours')
                        ->where('name_tour', 'like', "%{$userInput}%")
                        ->orWhere('location', 'like', "%{$userInput}%")
                        ->first();
        }

        return $tour;
    }

    /**
     * Loại bỏ dấu tiếng Việt khỏi chuỗi.
     *
     * @param string $str
     * @return string
     */
    protected function removeVietnameseAccents(string $str): string
    {
        $unicode = [
            'a' => 'á|à|ả|ã|ạ|ă|ắ|ằ|ẳ|ẵ|ặ|â|ấ|ầ|ẩ|ẫ|ậ',
            'd' => 'đ', 'e' => 'é|è|ẻ|ẽ|ẹ|ê|ế|ề|ể|ễ|ệ',
            'i' => 'í|ì|ỉ|ĩ|ị', 'o' => 'ó|ò|ỏ|õ|ọ|ô|ố|ồ|ổ|ỗ|ộ|ơ|ớ|ờ|ở|ỡ|ợ',
            'u' => 'ú|ù|ủ|ũ|ụ|ư|ứ|ừ|ử|ữ|ự', 'y' => 'ý|ỳ|ỷ|ỹ|ỵ',
            'A' => 'Á|À|Ả|Ã|Ạ|Ă|Ắ|Ằ|Ẳ|Ẵ|Ặ|Â|Ấ|Ầ|Ẩ|Ẫ|Ậ',
            'D' => 'Đ', 'E' => 'É|È|Ẻ|Ẽ|Ẹ|Ê|Ế|Ề|Ể|Ễ|Ệ',
            'I' => 'Í|Ì|Ỉ|Ĩ|Ị', 'O' => 'Ó|Ò|Ỏ|Õ|Ọ|Ô|Ố|Ồ|Ổ|Ỗ|Ộ|Ơ|Ớ|Ờ|Ở|Ỡ|Ợ',
            'U' => 'Ú|Ù|Ủ|Ũ|Ụ|Ư|Ứ|Ừ|Ử|Ữ|Ự', 'Y' => 'Ý|Ỳ|Ỷ|Ỹ|Ỵ',
        ];
        foreach ($unicode as $nonUnicode => $uni) {
            $str = preg_replace("/($uni)/i", $nonUnicode, $str);
        }
        return $str;
    }

    /**
     * Tìm tour theo tên từ input của người dùng.
     *
     * @param string $userInput
     * @return object|null
     */
    protected function findTourByName(string $userInput)
    {
        $normalizedUserInput = mb_strtolower($this->removeVietnameseAccents($userInput), 'UTF-8');

        if (preg_match('/(?:tour|tên|chuyến đi)\s+([\p{L}\s]+)/ui', $userInput, $matches)) {
            $name = trim($matches[1]);
            $normalizedName = $this->removeVietnameseAccents(mb_strtolower($name, 'UTF-8'));

            return DB::table('available_tours')
                ->where('name_tour', 'like', "%{$name}%")
                ->orWhere('name_tour', 'like', "%{$normalizedName}%")
                ->first();
        }
        return null;
    }

    /**
     * Tìm tour theo điểm đến từ input của người dùng.
     *
     * @param string $userInput
     * @return object|null
     */
    protected function findTourByLocation(string $userInput)
    {
        $normalizedUserInput = mb_strtolower($this->removeVietnameseAccents($userInput), 'UTF-8');

        if (preg_match('/(điểm đến|ở)\s*([\p{L}\s]+)/ui', $userInput, $matches)) {
            $location = trim($matches[2]);
            $normalizedLocation = $this->removeVietnameseAccents(mb_strtolower($location, 'UTF-8'));
            return DB::table('available_tours')
                           ->where('location', 'like', "%{$location}%")
                           ->orWhere('location', 'like', "%{$normalizedLocation}%")
                           ->first();
        } elseif (strlen($userInput) > 2) {
            return DB::table('available_tours')
                           ->where('location', 'like', "%{$userInput}%")
                           ->orWhere('location', 'like', "%{$normalizedUserInput}%")
                           ->first();
        }
        return null;
    }

    /**
     * Định dạng thông tin tour để trả lời người dùng và bao gồm ID tour.
     *
     * @param object $tour
     * @return array
     */
    public function formatTourReply(object $tour): array
    {
        $replyText = "✅ Tên tour: {$tour->name_tour}\n🏖 Điểm đến: {$tour->location}\n💰 Giá: " . number_format($tour->price, 0, ',', '.') . " VND\n📌 Mô tả: {$tour->description}";

        return [
            'text' => $replyText,
            'tour_id' => $tour->id,
            'quick_replies' => [
                ['text' => 'Xem tour khác', 'value' => 'tất cả tour hiện có'],
                ['text' => 'Liên hệ tư vấn', 'value' => 'tôi muốn nói chuyện với nhân viên'],
            ]
        ];
    }

    /**
     * Chuyển đổi giá trị chuỗi thành số thực, có xử lý đơn vị (triệu, nghìn).
     *
     * @param string $value
     * @param string|null $unit
     * @return float
     */
    protected function parsePriceValue(string $value, ?string $unit = null): float
    {
        $cleanValue = (float)str_replace(['.', ','], '', $value);
        $normalizedUnit = mb_strtolower(isset($unit) ? $unit : '', 'UTF-8');

        Log::info("Parsing price: value='{$value}', unit='" . (isset($unit) ? $unit : 'null') . "'");

        $parsedPrice = 0;

        switch ($normalizedUnit) {
            case 'triệu':
                $parsedPrice = $cleanValue * 1_000_000;
                break;
            case 'nghìn':
                $parsedPrice = $cleanValue * 1_000;
                break;
            default:
                $parsedPrice = $cleanValue;
                break;
        }

        Log::info("Parsed price result: " . $parsedPrice);
        return $parsedPrice;
    }

    /**
     * Tìm tour theo các tiêu chí giá từ input của người dùng.
     *
     * @param string $userInput
     * @return object|null
     */
    protected function findTourByPrice(string $userInput)
    {
        Log::info("findTourByPrice called with userInput: " . $userInput);

        if (stripos($userInput, 'giá') === false && stripos($userInput, 'khoảng giá') === false &&
            stripos($userInput, 'mức giá') === false && stripos($userInput, 'tầm') === false &&
            stripos($userInput, 'dưới') === false && stripos($userInput, 'trên') === false &&
            stripos($userInput, 'tối đa') === false && stripos($userInput, 'tối thiểu') === false &&
            stripos($userInput, 'nghìn') === false && stripos($userInput, 'triệu') === false) {
            return null;
        }

        $query = DB::table('available_tours');
        $foundPriceCriteria = false;
        $orderDirection = null;
        $targetPrice = null;

        $comparisonPatterns = [
            '/(?:thấp hơn|dưới|tối đa|bé hơn)\s*([\d.,]+)\s*(triệu|nghìn)?/ui' => function($v, $u) use (&$query, &$foundPriceCriteria, &$orderDirection, &$targetPrice) {
                $price = $this->parsePriceValue($v, $u);
                Log::info("Applying WHERE price < " . $price);
                $query->where('price', '<', $price);
                $foundPriceCriteria = true;
                $orderDirection = 'DESC';
                $targetPrice = $price;
            },
            '/(?:cao hơn|trên|hơn|tối thiểu|ít nhất)\s*([\d.,]+)\s*(triệu|nghìn)?/ui' => function($v, $u) use (&$query, &$foundPriceCriteria, &$orderDirection, &$targetPrice) {
                $price = $this->parsePriceValue($v, $u);
                Log::info("Applying WHERE price > " . $price);
                $query->where('price', '>', $price);
                $foundPriceCriteria = true;
                $orderDirection = 'ASC';
                $targetPrice = $price;
            },
        ];

        $rangePatterns = [
            '/từ ([\d.,]+)\s*(?:triệu|nghìn)? đến ([\d.,]+)\s*(?:triệu|nghìn)?/ui' => function($v1, $u1, $v2 = null, $u2 = null) use (&$query, &$foundPriceCriteria, &$orderDirection, &$targetPrice) {
                $minPriceInput = $this->parsePriceValue($v1, $u1);
                $maxPriceInput = $this->parsePriceValue($v2 ?: $v1, $u2 ?: $u1);

                $targetMin = min($minPriceInput, $maxPriceInput);
                $targetMax = max($minPriceInput, $maxPriceInput);

                $range = 1_000_000;

                $finalMin = max(0, $targetMin - $range);
                $finalMax = $targetMax + $range;
                Log::info("Applying WHERE BETWEEN price " . $finalMin . " AND " . $finalMax);
                $query->whereBetween('price', [$finalMin, $finalMax]);
                $foundPriceCriteria = true;
                $targetPrice = ($finalMin + $finalMax) / 2;
                $orderDirection = 'ASC';
            },
            '/khoảng ([\d.,]+)\s*[-–]\s*([\d.,]+)\s*(triệu|nghìn)?/ui' => function($v1, $v2, $u = null) use (&$query, &$foundPriceCriteria, &$orderDirection, &$targetPrice) {
                $minPriceInput = $this->parsePriceValue($v1, $u);
                $maxPriceInput = $this->parsePriceValue($v2, $u);

                $targetMin = min($minPriceInput, $maxPriceInput);
                $targetMax = max($minPriceInput, $maxPriceInput);

                $range = 1_000_000;

                $finalMin = max(0, $targetMin - $range);
                $finalMax = $targetMax + $range;
                Log::info("Applying WHERE BETWEEN price " . $finalMin . " AND " . $finalMax);
                $query->whereBetween('price', [$finalMin, $finalMax]);
                $foundPriceCriteria = true;
                $targetPrice = ($finalMin + $finalMax) / 2;
                $orderDirection = 'ASC';
            },
        ];

        $generalPricePatterns = [
            '/(?:khoảng|giá|mức giá|tầm)?\s*([\d.,]+)\s*(triệu|nghìn)?/ui' => function($v, $u) use (&$query, &$foundPriceCriteria, &$orderDirection, &$targetPrice) {
                $val = $this->parsePriceValue($v, $u);
                $range = 1_000_000;

                $minPrice = max(0, $val - $range);
                $maxPrice = $val + $range;
                Log::info("Applying WHERE BETWEEN price " . $minPrice . " AND " . $maxPrice);
                $query->whereBetween('price', [$minPrice, $maxPrice]);
                $foundPriceCriteria = true;
                $targetPrice = $val;
                $orderDirection = 'ASC';
            },
        ];

        $allPatterns = array_merge($comparisonPatterns, $rangePatterns, $generalPricePatterns);

        foreach ($allPatterns as $pattern => $action) {
            if (preg_match($pattern, $userInput, $matches)) {
                Log::info("Matched pattern: " . $pattern);
                Log::info("Regex matches: " . json_encode(array_slice($matches, 1)));

                $action(...array_slice($matches, 1));
                if ($foundPriceCriteria) {
                    if ($targetPrice !== null) {
                        if ($orderDirection === 'ASC' || $orderDirection === 'DESC') {
                            $query->orderBy('price', $orderDirection);
                        } else {
                            $query->orderBy(DB::raw("ABS(price - {$targetPrice})"), 'ASC');
                        }
                    }

                    Log::info("Final Query SQL: " . $query->toSql());
                    Log::info("Final Query Bindings: " . json_encode($query->getBindings()));

                    $result = $query->first();
                    Log::info("Query Result: " . json_encode($result));
                    return $result;
                }
            }
        }
        Log::info("No tour found based on price criteria for input: " . $userInput);
        return null;
    }
}