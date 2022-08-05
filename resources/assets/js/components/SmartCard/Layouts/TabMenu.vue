 <template>
	<div>
		<nav id="tabMenu" style="height: auto !important;">
		    <div class="nav-wrapper">
				<ul id="nav-mobile" class="left hide-on-med-and-down">
					<li>
						<a class='dropdown-trigger' href='#' data-target='fileDropdown'>File</a>
						<ul id='fileDropdown' class='dropdown-content'>
							<li><a href="#!">New Template</a></li>
							<li><a href="#!">Open File</a></li>
							<li><a href="#!">Save</a></li>
							<li class="divider" tabindex="-1"></li>
							<li><a href="#!">Exit</a></li>
						</ul>
					</li>
				</ul>
				<ul id="nav-mobile" class="left hide-on-med-and-down">
					<li>
						<a class='dropdown-trigger' href='#' data-target='editDropdown'>Edit</a>
						<ul id='editDropdown' class='dropdown-content'>
							<li><a href="#!">Edit 1</a></li>
							<li><a href="#!">Edit 2</a></li>
							<li><a href="#!">Edit 3</a></li>
							<li class="divider" tabindex="-1"></li>
							<li><a href="#!">Edit 4</a></li>
						</ul>
					</li>
				</ul>
				<ul id="nav-mobile" class="left hide-on-med-and-down">
					<li>
						<a class='dropdown-trigger' href='#' data-target='windowDropdown'>Window</a>
						<ul id='windowDropdown' class='dropdown-content'>
							<li><a href="#!">Window 1</a></li>
							<li><a href="#!">Window 2</a></li>
							<li><a href="#!">Window 3</a></li>
							<li class="divider" tabindex="-1"></li>
							<li><a href="#!">Window 4</a></li>
						</ul>
					</li>
				</ul>
				<ul id="nav-mobile" class="left hide-on-med-and-down">
					<li>
						<a class='dropdown-trigger' href='#' data-target='helpDropdown'>Help</a>
						<ul id='helpDropdown' class='dropdown-content'>
							<li><a href="#!">Help 1</a></li>
							<li><a href="#!">Help 2</a></li>
							<li><a href="#!">Help 3</a></li>
							<li class="divider" tabindex="-1"></li>
							<li><a href="#!">Help 4</a></li>
						</ul>
					</li>
				</ul>
		    </div>
		</nav>
	</div>
</template>

<style type="text/css">
	#tabMenu {
		padding-left: 19px; 
		padding-right: 19px;
	}

	#tabMenu ul a { 
		font-size: .8rem;
		color: #565656;
	}

	#tabMenu .dropdown-content {
		width: auto !important;
		height: auto !important;
	}

	#tabMenu .dropdown-content li {
		min-height: auto !important;
	}

	#tabMenu .dropdown-content li > a, 
	#tabMenu .dropdown-content li > span {
		font-size: 12px;
		padding: 2px 12px !important;
	}
</style>

<script>
	export default {
		mounted () {
			$(document).ready(function () {
				$('.dropdown-trigger').dropdown();
			})
		}
	}
</script>