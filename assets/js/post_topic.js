jQuery(($) => {
    $(document).on("click", ".fw-blog-category .tab", function () {
        var cateId = $(this).data('categoryid');
        showCategory(this, cateId);
    });
    jQuery('.blog-category-1 .tab').eq(0).trigger('click');
    jQuery('.blog-category-2 .tab').eq(0).trigger('click');
})


function showCategory(obj, parentCatId) {
    jQuery(obj).closest('.fw-blog-category').find('.blogCategorySwiper').css('opacity', '0');
    jQuery(obj).closest('.fw-blog-category').find('.category-content .loading').removeClass('hide');

    jQuery(obj).closest('.fw-blog-category').find('.tab').removeClass('active');
    jQuery(obj).addClass('active');

    jQuery(obj).closest('.fw-blog-category').find('.blogCategorySwiper').html('');
    jQuery.ajax({
        url: '/wp-admin/admin-ajax.php', // WordPress AJAX 처리 URL
        type: 'POST',
        data: {
            'action': 'load_subcategories', // WordPress에서 호출할 action
            'parent_cat_id': parentCatId // 파라미터로 부모 카테고리 ID 전달
        },
        success: function (response) {
            // 응답으로 받은 내용을 원하는 HTML 요소에 삽입합니다.
            jQuery(obj).closest('.fw-blog-category').find('.blogCategorySwiper').html(response);

            // Swiper 인스턴스를 초기화합니다.
            setTimeout(function () {
                new Swiper('.blogCategorySwiper', {
                    // 여기에 Swiper 옵션을 설정하세요.
                    loop: true,
                    slidesPerView: 1.2,
                    spaceBetween: 10,
                    breakpoints: {
                        768: {
                            slidesPerView: 4,
                            spaceBetween: 20,
                        }
                    },
                });
            }, 0);
            jQuery(obj).closest('.fw-blog-category').find('.blogCategorySwiper').css('opacity', '1');
            jQuery(obj).closest('.fw-blog-category').find('.category-content .loading').addClass('hide');
        }
    });
}