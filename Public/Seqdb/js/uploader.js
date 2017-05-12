$(document).ready(function() {
	// $('body').on('click', '.file-name', function(e) {
	// 	e.preventDefault();

	// 	$this = $(this);
	// 	$fileChooser = $this.parent().find('.file-name-chooser');
	// 	$fileChooser.click();
	// })

	// $('body').on('change', '.file-name-chooser', function(e) {
	// 	e.preventDefault();
	// 	$this = $(this);
	// 	$filename = $this.parent().find('.file-name');

	// 	var filepath = $this.val();
	// 	if(filepath.length) {
	// 		$filename.text(filepath.replace(/.*(\/|\\)/, ''));
	// 	} else {
	// 		$filename.text('点击选择文件');
	// 	}
	// })

	var uploader = WebUploader.create({
	    // swf文件路径
	    swf: PUBLIC_PATH + '/Uploader.swf',

	    // 文件接收服务端。
	    server: $this.attr('data-server'),

	    // 选择文件的按钮。可选。
	    // 内部根据当前运行是创建，可能是input元素，也可能是flash.
	    pick: '.data-file-picker',

	    // 不压缩image, 默认如果是jpeg，文件上传前会压缩一把再上传！
	    resize: false
	});
	

	$('body').on('click', '.data-file-upload', function(e) {
		e.preventDefault();
		$this = $(this);
		var $progress = $this.parent().find('.progress');
		var $picker = $this.parent().find('.file-name');

		

		uploader.on( 'fileQueued', function( file ) {
		    $progressBar = $progress.find('.progress-bar');
		    $progressBar.css("width", "0%").text("");

		    if(file.name.length) {
		    	$picker.text(file.name);
		    } else {
		    	$picker.text('点击选择文件');
		    }
		});
	})
});