<?php
class DiningController extends BaseController {
protected static $restful = true;
	public function getContentDataAttribute($data) {
		return json_decode($data);
	}
    public function pushData($name = NULL, $date = NULL)
    {

		if($date == NULL) {
			$date = date('m-d-Y', time());
		}
		if($name == NULL) {
			$name = "Earhart";
		}
        $data['shortName'] = $name;
        $data['name'] = $name . " Dining Hall";
		$data['date'] = $date;
		$url = "http://api.hfs.purdue.edu/menus/v2/locations/". $name . "/".$date."";
		if (Cache::has($name . "_" . $date)) {
			$json = Cache::get($name . "_" . $date);
		} else {
			$getfile = file_get_contents($url);
			$cacheforever = Cache::forever($name . "_" . $date, $getfile);
			$json = Cache::get($name . "_" . $date);
		}
		$json = json_decode($json, true);
		return View::make('dining', compact('data', 'json'));
    }
	public function getFood($id){
		$url = "http://api.hfs.purdue.edu/Menus/v2/V2Items/".$id."";
		if (Cache::has($id)) {
			$json = Cache::get($id);
		} else {
			$getfile = file_get_contents($url);
			$cacheforever = Cache::forever($id, $getfile);
			$json = Cache::get($id);
		}
		$json = json_decode($json, true);
		$data['id'] = $id;
		$data['name'] = $json['Name'];
        $reviews = Array(
            "rating" => 2.4,
            "reviews" => array(
                "user1" => "test",
                "user2" => "test2"
            )
        );
		return View::make('food', compact('data', 'json', 'reviews'));
	}
	
}