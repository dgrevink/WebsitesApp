<div class='ws-forumcommenter'>
	<div class='comments'>
		{$comments}
	</div>
	
	{if !$connected}
		<a href='#' id='commentconnectbutton'>Connectez-vous pour laisser un commentaire !</a>
	{else}
		<a name='comment'></a>{$username}, réagissez !:<br/>
		<form method='post' action='#comment'>
			<textarea name='comment' class='textareacomment'></textarea><br/>
			<input type="hidden" name="posted" value="posted" />
			<input type="submit" class='buttonsubmit' value="Envoyer"/>
		</form>
	{/if}
	{literal}
	<script language="javascript">
		var connected = '{/literal}{$connected}{literal}';
		$(document).ready(function(){
			$('#commentconnectbutton').live('click',function(e){
				e.preventDefault();
				$('html, body').animate({
				    scrollTop: $(".ws-login-form-container").offset().top
				}, 1000, function(){
					$('#ws-login-username').focus();
				});
			});
			$('.buttonreact').live('click',function(e){
				e.preventDefault();
				if (connected == '1') {
					$('#replyform-' + $(this).attr('rel')).slideToggle('fast');
				}
				else {
					$('html, body').animate({
					    scrollTop: $(".ws-login-form-container").offset().top
					}, 1000, function(){
						$('#ws-login-username').focus();
					});
				}
			});
			
			// progressive enhancement for replies
			$('.subcommentform .buttonsubmit').live('click',function(e){
				e.preventDefault();
				var relatedpostid = $(this).prevAll('.relatedpostid').val();
				var threadid = $(this).prevAll('.threadid').val();
				var comment = $(this).prevAll('.textareacomment').val();
				var title = $(this).prevAll('.title').val();
				var where = $(this).parent().parent().parent();
				$(this).prevAll('.textareacomment').val('');
				$(this).parent().parent().slideUp('fast');
				var jqxhr = $.ajax({
						url: "/WForumCommenter/dopost",
						type: "POST",
						data: {
							'comment': comment,
							'relatedpostid': relatedpostid,
							'threadid': threadid,
							'title': title
						},
						dataType: "html"
					})
				    .done(function(data) {
//						where.css('background-color', 'orange');
				    	$(data).insertAfter(where);
					})
				    .fail(function() { alert("error"); })
				    .always(function(data) { 
				     });
			});
			
			
		});
	</script>
	{/literal}
</div>
