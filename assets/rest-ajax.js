jQuery(function ($) {
	/**
	 * Class LeilacAjax.
	 */
	class LeilacAjax {
		/**
		 * Contructor.
		 */
		constructor() {
            this.ajaxNonce = siteConfig?.ajax_nonce ?? "";
			this.isRequestProcessing = false;

			this.filterJobs = $("#filter-jobs");
			this.filterJobsLocation = $("#filter-jobs-location");
			this.loadMoreButton = $(".load-more");
			this.openProfileButton = $(".open-profile");
			
			this.init();
		}

		init() {
			this.filterJobs.on("input", () => this.loadFilteredJobs());
			this.filterJobsLocation.on("input", () => this.loadFilteredJobs());
			this.loadMoreButton.on("click", (e) => this.loadMorePosts(e));
			this.openProfileButton.on("click", (e) => this.loadProfile(e));
		}

		loadFilteredJobs() {
			if (this.isRequestProcessing) {
				this.currentRequest.abort();
			}
			
			this.isRequestProcessing = true;
			
			let container = $("#jobs-wrapper");
			
			container.addClass("loading");
			
			let keyword = this.filterJobs.val();
			let location = this.filterJobsLocation.val();
			
			let data = {
				post_type: "job",
				ajax_nonce: this.ajaxNonce,
			}
			
			data.filter = "";
			
			if (keyword != "") {
				data.filter += "keyword,";
				data.keyword = keyword;
			}
			
			if (location != "") {
				data.filter += "field,";
				data.key = "location";
				data.value = location;
			}

			this.currentRequest = $.ajax({
				url: "/wp-json/leilac/v1/jobs",
				type: "GET",
				data: data,
				success: (response) => {					
					container.empty();
					
					if (!response.length) {
						container.append("<h2>No jobs found</h2>");
					}

					container.append(response);
					container.removeClass("loading");
					this.isRequestProcessing = false;
				},
				error: (response) => {
					console.log(response);
					this.isRequestProcessing = false;
				},
			});
		}
		
		loadMorePosts(e) {
			const button = $(e.target);
			const wrapper = $("#" + button.data("wrapper") + " .content-wrapper");
			const postTypes = button.data("post-types");
			const cat = button.data("cat");
			const offset = button.data("offset");
			const page = button.data("page");
			const perPage = button.data("per-page");
			const totalPagesCount = parseInt(button.data("max-pages"));
			
			const mobileUser = $(window).width() <= 1024;
			let swiper = mobileUser ? window[button.data("wrapper") + "Swiper"] : null;
			
			if ( ! page || this.isRequestProcessing ) {
				return null;
			}

			const nextPage = parseInt( page ) + 1; // Increment page count by one.
			this.isRequestProcessing = true;

			$.ajax( {
				url: "/wp-json/leilac/v1/content",
				type: 'GET',
				data: {
					post_types: postTypes,
					cat: cat,
					offset: offset,
					page: nextPage,
					per_page: perPage,
					ajax_nonce: this.ajaxNonce,
				},
				success: (response) => {
					button.data('page', nextPage);
					
					if (mobileUser) {
						swiper.appendSlide(response);
						swiper.update();
					} else {
						wrapper.append(response);
					}
					
					if (nextPage + 1 > totalPagesCount) {
						button.remove();
					}
					
					this.isRequestProcessing = false;
				},
				error: (response) => {
					console.log(response);
					this.isRequestProcessing = false;
				},
			} );
		}
		
		loadProfile(e) {
			const button = $(e.target).parent();
			const profile = button.data("profile");
			this.isRequestProcessing = true;

			$.ajax( {
				url: "/wp-json/leilac/v1/team-member",
				type: 'GET',
				data: {
					team_member: profile,
					ajax_nonce: this.ajaxNonce,
				},
				success: (response) => {
					console.log(response);
					
					let profile = $(".team-member-profile");
					
					profile.find('.photo > .elementor-widget-container img[src*="Loading_icon.gif"]').eq(0).hide();
					profile.find('.photo > .elementor-widget-container').eq(0).append(response.photo);
					profile.find('.name h4').eq(0).html(response.name);
					profile.find('.role p').eq(0).html(response.role);
					profile.find('.elementor-tabs-content-wrapper .elementor-tab-content[data-tab="1"]').eq(0).html(response.biography);
					
					let loadContent = (content, tab) => {
						if(content.length == 0) {
							profile.find('.elementor-tabs-wrapper .elementor-tab-title[data-tab="' + tab + '"]').remove();
							profile.find('.elementor-tabs-content-wrapper .elementor-tab-content[data-tab="' + tab + '"]').remove();
						} else {
							profile.find('.elementor-tabs-content-wrapper .elementor-tab-content[data-tab="' + tab + '"]').eq(0).html(content);
						}
					};
					
					loadContent(response.videos, 2);
					loadContent(response.podcasts, 3);
					loadContent(response.insights, 4);
					loadContent(response.conferences, 5);
					loadContent(response.whitepapers, 6);
					loadContent(response.reports, 7);
					loadContent(response.posts, 8);
					
					this.isRequestProcessing = false;
				},
				error: (response) => {
					console.log(response);
					this.isRequestProcessing = false;
				},
			} );
		}
	}

	$(document).ready(() => {
		new LeilacAjax();
	});
});