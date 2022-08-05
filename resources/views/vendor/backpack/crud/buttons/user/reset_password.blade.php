@if ($crud->hasAccess('reset-password'))
	@if($entry->employee)
		<a href="javascript:void(0)" onclick="resetPassword(this)" data-id="{{ $entry->getKey() }}" data-email="{{ $entry->email }}" data-route="{{ url($crud->route.'/reset-password/'.$entry->getKey()) }}" class="btn btn-xs btn-default action-btn" data-button-type="resetPassword" title="Reset Password">
			<i class="fa fa-undo-alt"></i>
		</a>

		<script>
			if (typeof resetPassword != 'function') {
			  $("[data-button-type=resetPassword]").unbind('click');

			  function resetPassword(button) {
				// ask for confirmation before deleting an item
				// e.preventDefault();
				var button = $(button);
				var route = button.attr('data-route');
				var btnEmail = button.attr('data-email');
				$.confirm({
				  title: "Confirmation",
				  content: "<h5 class='text-center'>Are you sure you want to reset the password of <a href='mailto:" + btnEmail + "'>" + btnEmail + "</a> ?</h5>",
				  icon: "fa fa-warning",
				  buttons: {
				  	cancel: {
					  text: "Cancel",
					  value: null,
					  visible: true,
					  btnClass: "btn-default",
					  closeModal: true,
					},
				  	confirm: {
					  text: "Reset Password",
					  value: true,
					  visible: true,
					  btnClass: 'btn-warning',
					  action: function(){
					  	var url = route;
						var form = $('<form action="' + url + '" method="post">@csrf </form>');
						$('body').append(form);
						form.submit();
					  }
					}
				  },
				});

		      }
			}
		</script>
	@endif
@endif