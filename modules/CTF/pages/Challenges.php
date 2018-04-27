<?php
use Root3287\classes\User as User;
use Root3287\classes\Redirect as Redirect;
use Root3287\classes\DB as DB;
use Root3287\classes\Input as Input;
use Root3287\classes\Token as Token;
use Root3287\classes\Validation as Validation;
use Root3287\classes\Session as Session;
use Root3287\classes\Output as Output;

$user = new User();

if(Input::exists()){
	if(Token::check(Input::get('token'))){
		$val = new Validation();
		$validate = $val->check($_POST, [
			"flag"=>[
				"required" => true,
			],
		]);

		if($validate->passed()){
			$flag = DB::getInstance()->get('ctf_challenges', ["id", "=", Input::get('challengeID')])->first();
			DB::getInstance()->insert("ctf_attempt", [
					"team" => (int)$user->data()->team,
					"answer" => Output::clean(Input::get("flag")),
					"challenge" => Input::get("challengeID"),
			]);
			if($flag->flag == Output::clean(Input::get('flag'))){
				DB::getInstance()->insert("ctf_challenge_complete", [
					"team" => $user->data()->team,
					"challenge" => Output::clean(Input::get("challengeID")),
					"completed" => 1,
					"date" => date("Y-m-d H:i:s"),
				]);
				$cp = DB::getInstance()->get("ctf_teams", ["id", "=", $user->data()->team])->first()->points;
				DB::getInstance()->update("ctf_teams", $user->data()->team, [
					"points" => $cp+$flag->points,
				]);
				Session::flash("alert-success", "Flag Found! ".$flag->name);
			}else{
				Session::flash("alert-danger", "Flag not Found! ".$flag->name);
			}
		}
	}
}
?>
<!DOCTYPE html>
<html>
<head>
	<?php include 'assets/head.php';?>
</head>
<body>
	<?php include 'assets/nav.php';?>
	<div class="container-fluid mt-4">
		<?php if(Session::exists('alert-danger')): ?>
			<div class="alert alert-danger"><?php echo Session::flash('alert-danger'); ?></div>
		<?php endif; ?>
		<?php if(Session::exists('alert-success')): ?>
			<div class="alert alert-success"><?php echo Session::flash('alert-success'); ?></div>
		<?php endif; ?>
		<h1>Challenges</h1>
		<?php if($user->data()->team == 0): ?>
			<div class="alert alert-warning">You have to be on a <a class="alert-link" href="/team/join/">team</a> to do these challenges</div>
		<?php else: ?>
		<?php foreach (DB::getInstance()->get("ctf_category", ["1", '=', '1'])->results() as $category) {?>
			<h3><?php echo $category->name; ?></h3>
			<div class="card-columns">
			<?php foreach(DB::getInstance()->get("ctf_challenges", ["category", "=", $category->id])->results() as $challenge): ?>
				<div class="card">
					<div class="card-body">
						<h5 class="card-title"><?php echo $challenge->name;?></h5>
						<h6 class="card-subtitle text-muted"><?php echo $challenge->description; ?></h6>
						<p class="card-text"><?php echo $challenge->hint; ?></p>
			<?php 
			$completed = DB::getInstance()->get('ctf_challenge_complete', ["challenge", "=", $challenge->id])->first();
			if(!$completed || $completed->completed != 1): ?>
						<button class="btn btn-outline-primary" data-toggle="modal" data-target="#submitModel" data-id="<?php echo $challenge->id; ?>" data-title="<?php echo $challenge->name; ?>" data-points="<?php echo $challenge->points; ?>" >More</button>
			<?php endif; ?>
					</div>
				</div>
			<?php endforeach; ?>
			</div>
		<?php } ?>
		<?php endif; ?>
	</div>

	<div class="modal fade" id="submitModel" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
	  <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
	    <div class="modal-content">
	      <div class="modal-header">
	        <h5 class="modal-title" id="exampleModalLongTitle">Modal title</h5>
	        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
	          <span aria-hidden="true">&times;</span>
	        </button>
	      </div>
	      <div class="modal-body">
	        <p id="description"></p>
	        <p id="hint"></p>
	      </div>
	      <div class="modal-footer">
	      	<form action="" method="POST" class="form-inline" autocomplete="off">
	      		<div class="form-group mx-1">
	      			<input type="text" name="flag" class="form-control">
	      			<input id="challengeID" type="hidden" name="challengeID" value="">
	      			<input type="hidden" name="token" value="<?php echo Token::generate(); ?>">
	      		</div>
	      		<div class="form-group mx-1"><button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button></div>
	      		<div class="form-group mx-1"><input type="submit" value="Submit" class="btn btn-primary"></div>
	    	</form>
	      </div>
	    </div>
	  </div>
	</div>
	<?php include 'assets/foot.php';?>
	<script>
		$(document).ready(function(){
			$('#submitModel').on('show.bs.modal', function (event) {
  				var button = $(event.relatedTarget) // Button that triggered the modal
  				
  				var qID = button.data('id')
  				var qName = button.data('title')
  				var qPoints = button.data('points')
  				
  				var modal = $(this);
  				$.getJSON("/api/challenges/"+qID+"/", function(result){
  					console.log(result.hint);
  					modal.find('#description').text(result.description);
  					modal.find('.modal-title').text(result.name);
  					modal.find('#challengeID').val(result.id);
  					modal.find('#hint').html(result.hint);
  				});
				//modal.find('.modal-body input').val(recipient)
			});
		});
	</script>
</body>
</html>