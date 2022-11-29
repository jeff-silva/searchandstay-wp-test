<?php

function demo_admin_settings() {
	?>
	
	<div id="app">
		<v-app class="mt-5" style="background:none;">
			<v-main>
				
				<v-snackbar v-model="settings.saved" color="success">
					<v-icon>mdi-check</v-icon> Settings have been saved
				</v-snackbar>

				<form @submit.prevent="settingsSave()">
					<v-card title="Search and Stay Settings">
						<v-card-text>
							<v-row>
								<v-col cols="12" md="8">
									<v-text-field label="Endpoint URL" v-model="settings.data.demo_api_url" :hide-details="true"></v-text-field>
								</v-col>
								<v-col cols="12" md="4">
									<v-text-field label="Token" v-model="settings.data.demo_api_token" :hide-details="true"></v-text-field>
								</v-col>
							</v-row>
						</v-card-text>
						<v-card-actions>
							<v-spacer></v-spacer>
							<v-btn type="submit" color="success" :loading="settings.saving">Salvar</v-btn>
						</v-card-actions>
					</v-card>
				</form>
				<br><br>

				<v-btn @click="requestApi()" :loading="status.loading">Response test</v-btn>
				<v-alert color="success" v-if="status.data" class="mt-5">{{ status.data.message }}</v-alert>
				<pre class="pa-4 mt-5 bg-success" style="color:lime;" v-if="status.data">{{ status.data.data }}</pre>
				<v-alert color="error" v-if="status.error" class="mt-5">{{ status.error.data.message }}</v-alert>
				<pre class="pa-4 mt-5 bg-error" style="background:#000; color:lime;" v-if="status.error">{{ status.error.data }}</pre>
			</v-main>
		</v-app>
	</div>

	<style>
		.v-application__wrap {
			min-height: auto;
		}

		.v-field__field input {
			background: none;
			padding: 8px !important;
			margin-top: 10px;
			border: none !important;
			outline: none !important;
			box-shadow: none !important;
		}
	</style>

	<script>
		const app = Vue.createApp({
			data() {
				return {
					status: {
						loading: false,
						data: false,
						error: false,
					},
					settings: {
						saving: false,
						saved: false,
						error: false,
						data: <?php echo json_encode(Demo::settings()); ?>,
					},
				};
			},

			methods: {
				async settingsSave() {
					this.settings.saving = true;
					this.settings.saved = false;
					try {
						const { data } = await axios.post('<?php echo Demo::endpointUrl('/settings?_wpnonce'); ?>', this.settings.data);
						this.settings.data = data;
						this.settings.saved = true;
					}
					catch(err) {
						this.settings.error = err.response.data;
					}
					this.settings.saving = false;
				},

				async requestApi() {
					this.status.loading = true;
					this.status.error = false;
					this.status.data = false;
					try {
						const { data } = await axios.get(this.settings.data.demo_api_url, {
							headers: {'SearchAndStayApiToken': this.settings.data.demo_api_token},
						});
						this.status.data = data;
					}
					catch(err) {
						this.status.error = err.response;
					}
					this.status.loading = false;
				},
			},
		})
		.use(Vuetify.createVuetify())
		.mount('#app');
	</script>

	<?php
}

add_action('admin_menu', function() {
	$title = 'Search and Stay Settings';
	add_menu_page($title, $title, 'manage_options', __FILE__, 'demo_admin_settings', 'dashicons-admin-generic', 10);
	add_submenu_page('options-general.php', $title, $title, 'manage_options', 'wp-translations-search', 'demo_admin_settings');
});
