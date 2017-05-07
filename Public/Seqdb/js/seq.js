$(document).ready(function() {
	$('body').on('click', '.remove-seqresult', function(e){
        var $this = $(this);
        e.preventDefault();

        swal({
            title: '删除测序文库记录',
            text: "删除该测序文库记录后，所有相关的测序结果和数据都将被删除。确认删除测序文库记录？",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#9f9f9f',
            confirmButtonText: '确定删除',
            cancelButtonText: '取消'
        }).then(function () {
            window.location.href = $this.attr('href');
        }).catch(swal.noop);
    });

    $('body').on('click', '.remove-seqreport', function(e){
        var $this = $(this);
        e.preventDefault();

        swal({
            title: '删除测序报告文件',
            text: "确认删除测序报告文件？",
            type: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#9f9f9f',
            confirmButtonText: '确定删除',
            cancelButtonText: '取消'
        }).then(function () {
            window.location.href = $this.attr('href');
        }).catch(swal.noop);
    });

    $('.upload-report').on('click', function(e) {
        e.preventDefault();
        $('#report-upload').click();
    });

    $('#report-upload').on('change', function(e) {
        $('#report-upload-form').submit();
    });

    $('#submit-seq-form').on('click', function(e) {
        $('#seq-form').submit();
    });
})