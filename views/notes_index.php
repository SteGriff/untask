<?php 

global $PATH;

//Populated if one
$note = null;	
//Populated if array
$notes = null;

if ($this->notes){	//Check there is any data
	if ( is_array($this->notes) ){
		$notes = $this->notes;
	}
	else{
		$note = $this->notes;
	}
}

$noteHtml = array(
	'open' => "<p class='note'>",
	'close' => "</p>"
);

function note_delete_tag($id){
	return "<a class='d' onclick='deleteNote($id, this)'>&#x2705;</a>";
}

function draw_note($n, $html){
	echo note_html($n->Content, $n->ID, $html);
}
function note_html($content, $id, $html){
	return $html['open'] . $content . note_delete_tag($id) . $html['close'];
}

function draw_rich_note($n){
	echo "<p class='rich'>{$n->Content}</p>";
}

?>
<!DOCTYPE HTML>
<html manifest="untask.appcache">
<head>
<title>Untasks</title>
<?php require "facets/head.php"; ?>

<link rel="stylesheet" type="text/css" href="/views/gfx/notes.css">
</head>
<body>
<?php global $PATH; require "facets/logo.php"; ?>
	
	<div id="wrap">
		
		<div id="noteRegion" class="group">
		
		<img id="preload" src="/views/gfx/img/creating.gif" alt="Preparing...">
		
		<?php
		if (isset($notes)){
			foreach($notes as $n){
				draw_note($n, $noteHtml);
			}
		}
		elseif (isset($note)){
			//1 note
			draw_rich_note($n);
		}
		else{
			//Nothing at all
			if ($this->error){
				var_dump($this->error);
			}
			else{
				echo '<p class="tip removable">Type a note below and press return or click on +</p>';
			}
		}
		?>
		
		</div>
	
	<div id="noteCreate">
		<input type="text" name="Content" id="noteContent">
		<button type="submit" onclick="submitNote()" id="noteAdd">+</button>
	</div>
	
	</div>
	
	<script type="text/javascript" src="<?php echo "$PATH/views/js/jquery.js"; ?>"></script>
	<script type="text/javascript">
		$(function(){
			//On enter key in field, submit note. Enter on button already does.
			$("#noteContent").keyup(function(event){
				if (event.keyCode == 13){submitNote();}
			});
			$('img#preload').hide();
		});

		var controller = '<?php echo $PATH; ?>/note';
		
		// Create a temporary note row with (true) and remove it with (false)
		// [When waiting for a note to be committed to server]
		function creating(show){
			if (show){
				//Make and display a throbber
				var throbber = "<p class=\"throbber removable\"><em>Sending... </em><img src=\"/views/gfx/img/creating.gif\" alt=\"Sending data\"></p>";
				$('#noteRegion').append(throbber);
			}
			else{
				$('.removable').remove();
			}
		}
		
		//Append a note with content string and id to the list of notes
		function appendNote(noteContent, id){
			var noteHtml = "<?php echo note_html("\" + noteContent + \"", "JSNoteIDJSNoteID", $noteHtml); ?>";
			noteHtml = noteHtml.replace("JSNoteIDJSNoteID", id);
			$('#noteRegion').append(noteHtml);
			var $notes = $('#noteRegion');
			$notes.scrollTop($notes[0].scrollHeight);
		}
		function submitNote(){

			var $contentField = $('#noteContent');
			var noteContent = $contentField.val();
			
			// Don't submit if empty
			if (!noteContent){ return; }
			
			// Clear input field
			$contentField.val(""); 
			
			// Display "Submitting..." throbber
			creating(true);
			
			$.post(
				controller,
				{ "Content" : noteContent },
				function(data, ts, xhr) {
					creating(false);
					appendNote(noteContent, data);
					return false;
				}
			);
			return false;
		}
		function deleteNote(id, ui){
			//Change tick to recycling icon and unbind its click event
			ui.innerHTML = "&rarr; &#x2672;"
			$(ui).click(function(){});
			
			$.post(
				controller + '/' + id + '/delete',
				{ "Sure" : "true" },
				function(data, ts, xhr) {
					//id = xhr.getResponseHeader("ID"); //Can get ID if needed
					var pNote = ui.parentNode;
					pNote.parentNode.removeChild(pNote);
					return false;
				}
			);
		}
	</script>
	
</body>
</html>