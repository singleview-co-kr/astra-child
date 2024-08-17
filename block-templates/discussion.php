<?php
$s_discussion_title = get_field('discussion_title');
$o_x2board = get_field('x2board_id');
$s_csv_query = get_field('csv_query');
$o_param = new stdClass();
$o_param->n_posts_per_page = get_field('list_count');
if( $o_x2board ) {
	$n_board_id = $o_x2board[0]->ID;
}
else {
	$n_board_id = 0;
}
unset($o_x2board);

// load x2board API
require_once X2B_PATH . 'api.php';
// 묻고 답하기 검색
$a_qna_rst = X2board\Api\get_quick_search( $n_board_id, $s_csv_query, $o_param );
unset($o_param);
?>
    
<div class="sv-custom-block">
    <div class="discussion">
		<p class="sec-label"><?php echo $s_discussion_title != '' ? $s_discussion_title : '이 글과 연관된 Q&A' ?></p>
		<div id="x2board-qna-list" class="latest-qna-list">
			<?php if( empty( $a_qna_rst ) ): ?>
				연관된 논의가 없습니다.
			<?php else : ?>
				<!-- 리스트 시작 -->
				<?php if (is_admin()) : ?>
                	<?php foreach( $a_qna_rst as $n_idx => $o_post ): ?>
						<div class="accordion-item">
							<h2><span class="q">Q.</span> <?php echo $o_post->title ?></h2>
							<div><?php echo nl2br($o_post->content) ?><BR><BR>
								<a href='<?php echo $o_post->permalink ?>'>자세히 보기</a>
							</div>
						</div>
					<?php endforeach ?>
            	<?php else : ?>
					<div class="accordion accordion-flush" id="accordionQna">
						<?php foreach( $a_qna_rst as $n_idx => $o_post ): ?>
							<div class="accordion-item">
								<h2 class="accordion-header" id="flush-heading<?php echo $n_idx ?>">
									<button class="accordion-button collapsed" type="button" data-bs-toggle="collapse" data-bs-target="#flush-collapse<?php echo $n_idx ?>" aria-expanded="false" aria-controls="flush-collapse<?php echo $n_idx ?>">
										<span class="q">Q.</span> <?php echo $o_post->title ?>
									</button>
								</h2>
								<div id="flush-collapse<?php echo $n_idx ?>" class="accordion-collapse collapse" aria-labelledby="flush-heading<?php echo $n_idx ?>" data-bs-parent="#accordionQna">
									<div class="accordion-body"><?php echo nl2br($o_post->content) ?><BR><BR>
									<a href='<?php echo $o_post->permalink ?>'>자세히 보기</a></div>
								</div>
							</div>
						<?php endforeach ?>
					</div>
				<?php endif ?>
			<?php endif ?>
			<!-- 리스트 끝 -->
		</div>
	</div>
</div>
<?php
unset($a_qna_rst);
?>