@extends('layout')

@section('css')
@parent
<style>
#heading {
	display: none;
}
</style>
@stop

@section('content')
<input type="hidden" id="id_data" data-user="{{Auth::id()}}">
<div class="row">
	<div class="col-md-10"><h1>{{$data['name']}}</h1></div>
    <div class="col-md-2"><a href="#" class="pull-right"><img title="profile image" class="img-circle img-responsive" src="https://www.gravatar.com/avatar/{{md5(strtolower(trim($data['user']->email)))}}?&amp;r=x&amp;d=identicon&amp;s=100" alt="Profile Picture"></a></div>
	<hr/>
</div>
<hr/>
<div class="row">
	<div class="col-md-3">
		<ul class="list-group">
			<li class="list-group-item text-center"><b>User Information</b></li>
			<li class="list-group-item text-right"><span class="pull-left"><strong>Joined</strong></span> {{date("F j, Y", strtotime($data['user']->created_at))}}</li>
			<li class="list-group-item text-right"><span class="pull-left"><strong>Email</strong></span> {{$data['user']->email}}</li>
			<li class="list-group-item text-center">You have <b>{{$data['numReviews']}} {{Str::plural('review', $data['numReviews'])}}</b></li>
			<li class="list-group-item text-center">You have <b>{{$data['numFav']}} {{Str::plural('favorite', $data['numFav'])}}</b></li>
		</ul> 
	</div>
	<div class="col-md-9">
		<ul id="myTab" class="nav nav-tabs" role="tablist">
			<li class="active"><a href="#reviews" role="tab" data-toggle="tab">Reviews</a></li>
			<li><a href="#favorite" role="tab" data-toggle="tab">Favorite Items</a></li>
		</ul>
	<div id="myTabContent" class="tab-content">
		<div class="tab-pane fade in active" id="reviews">
			<br/>
			<table class="table table-striped table-bordered">
				<thead>
					<tr>
						<th>Food Name</th>
						<th>Rating</th>
						<th>Review</th>
					</tr>
				</thead>
				<tbody>
				@foreach($data['reviews'] as $review)
					<tr>
						<td>
						<a href="{{action('DiningController@getFood', array('id' => urlencode($review['name'])))}}#{{$review['comment_id']}}">{{$review['name']}}</a></td>
						<td>{{$review['rating']}}</td>
						<td>{{{substr($review['comment'], 0, 30)}}}</td>
					</tr>
				@endforeach
				</tbody>
			</table>
		</div>
		<div class="tab-pane fade" id="favorite">
			<div class="list-group">
				<br/>
				@foreach($data['favorites'] as $fav)
					{{link_to_action('DiningController@getFood', $fav['name'], array('id' => urlencode($fav['name'])), array('class' => 'list-group-item'))}}
				@endforeach
			</div>
		</div>
    </div>
	</div>
</div>

<div class="row">
<h2>Other Settings</h2>
<hr/>
	<div class="col-md-6">
	<h4>Email Settings</h4>
	{{Form::checkbox('allowemail', 'settingToggle_allowemail', $data['user']->settingToggle_allowemail)}} Allow Nightly Emails<br>
	</div>
	<div class="col-md-6">
		<h4>Dietary Preferences</h4>
		{{Form::checkbox('non vegetarian items', 'settingToggle_vegetarian', $data['user']->settingToggle_vegetarian)}} Hide non-vegetarian items<br>
		{{Form::checkbox('dairy items', 'settingToggle_dairy', $data['user']->settingToggle_dairy)}} Hide items containing dairy<br>
		{{Form::checkbox('soy idems', 'settingToggle_soy', $data['user']->settingToggle_soy)}} Hide items containing soy<br>
		{{Form::checkbox('egg items', 'settingToggle_egg', $data['user']->settingToggle_egg)}} Hide items containing eggs<br>
		{{Form::checkbox('wheat items', 'settingToggle_wheat', $data['user']->settingToggle_wheat)}} Hide items containing wheat<br>
		{{Form::checkbox('gluten items', 'settingToggle_gluten', $data['user']->settingToggle_gluten)}} Hide items containing gluten<br>

		<div class="alert alert-success" role="alert" id="postUpdateAlert" hidden="true">
			<div id="postUpdateAlertMessage">herp</div>
		</div>
		<input type="hidden" id="user" data-user="{{Auth::id()}}">
	</div>
</div>

<script>

    $(function () {
        $(' [value^="settingToggle_"]:checkbox').change(function()
        {
           console.log(this.value + "|" + this.checked + " userID: "+$('#user').data("user"));
            type = this.name
            hideOrShow = this.checked
            $.post("/user/updateSettingsToggles",
                {
                    user_id:$('#user').data("user"),
                    settingToggle:this.value,
                    value:this.checked

                },
                function(data,status)
                {
                    console.log("Data: " + data + "\nStatus: " + status);
                    $("#postUpdateAlert").prop("hidden", false);
                    if(hideOrShow==true)
                    {
                        $("#postUpdateAlertMessage").text(type+" will be hidden now.");
                    }
                    else
                    {
                        $("#postUpdateAlertMessage").text(type+" will be shown now.");
                    }

                });

        });
    });

</script>
@stop