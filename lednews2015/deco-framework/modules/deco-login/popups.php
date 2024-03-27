<?php
add_action( 'wp_footer', 'deco_login_popup', 999 );
function deco_login_popup() {
	if ( ! is_user_logged_in() ) { ?>
		<div class="overlayer">
			<div class="login-block">
				<div class="login-form-block">
					<div class="flipper">
						<div class="login-form forgot-pass">
							<i class="close-modal" role="close_form"></i>

							<h3>Відновлення <br>паролю</h3>

							<form action="" method="post" id="session_reset" class="fields" autocomplete="off">
								<div class="inputs-group">
									<div class="text-input">
										<input type="text" name="user[email]" placeholder="Логін або e-mail"></div>
								</div>
								<button class="btn btn-green">Скинути пароль</button>
							</form>
							<p>На вказану вами адресу електронної пошти буде вислана інструкція з відновлення пароля</p>

							<p class="for_pass"><a href="#" role="toggle_forgot_password_form">← Назад</a></p>
						</div>

						<div class="login-form">
							<i class="close-modal" role="close_form"></i>
                            <div class="user-img"></div>
							<h3>Вхід на сайт</h3>



							<form action="" method="post" class="fields" id="sessions_new" autocomplete="off">
								<div class="inputs-group">
									<div class="text-input">
										<input type="text" name="user[email]" class="first" placeholder="Логін або e-mail"
										       value="">
									</div>
									<div class="text-input">
										<input type="password" name="user[password]" class="last" placeholder="Пароль" value="">
									</div>
								</div>

                            <p class="separator">Або ввійти через соц.мережі</p>
                            <ul class="social-links">
                                <li><a href="/auth/facebook" class="fb">Facebook</a></li>
                                <li><a href="/auth/google_oauth2" class="gplus">Google +</a></li>
                            </ul>

							<p class="for_pass"><a href="#"  role="toggle_forgot_password_form">Забули пароль?</a></p>

                        <button class="btn btn-green" id="signin">Вхід</button>
                        </div>
                        </form>
					</div>
				</div>
			</div>
		</div>
	<?php }
} ?>