var tasksCounter = 0;
var tasksPool = {};
var defaultParams = {
    'checkForAlreadyRunning' : false,
    'taskStatusArea' : false,
    'customStatusArea' : false,
    'progressArea' : '#progressArea',
    'resultArea' : '#resultArea',
    'errorsArea' : '#errorsArea',
    'showPreloader' : function () {},
    'hidePreloader' : function () {},
};


function startNewBackgroundTask(windowMode, params, pixWidht, pixHeight, title){
    tasksCounter++;
    var taskId = 'backgroundTask_' + tasksCounter;
    var backgroundWrapper = '#backgroundWrapper_' + tasksCounter;
    var backgroundTaskArea = '#backgroundTaskArea_' + tasksCounter;

 //   console.log(params);
    tasksPool[taskId] = new BackgroundTask(params);
    tasksPool[taskId].params = params;
    for (key in defaultParams) {
        tasksPool[taskId].params[key] = defaultParams[key];
    }
    tasksPool[taskId].params['backgroundWrapper'] = backgroundWrapper;
    tasksPool[taskId].params['backgroundTaskArea'] = backgroundTaskArea;
    tasksPool[taskId].params['progressArea'] = defaultParams['progressArea'] + '_' + tasksCounter;
    tasksPool[taskId].params['resultArea'] = defaultParams['resultArea'] + '_' + tasksCounter;
    tasksPool[taskId].params['errorsArea'] = defaultParams['errorsArea'] + '_' + tasksCounter;

    tasksPool[taskId].init();

    drawTaskWindow(tasksCounter, title);
    switch (windowMode) {
        case 'modal':
            //------------------------------------------------------------------ вывод на экран
            var winH = $(window).height();
            var winW = $(window).width();
            $(backgroundTaskArea)
                .css('width', pixWidht)
                //.css('height', pixHeight)
              //  .css('top', winH / 2 - pixHeight / 1.5)
                .css('top', 50)
                .css('left', winW / 2 - pixWidht / 2)
                .addClass('background-area-modal').fadeIn();
            $(backgroundWrapper).addClass('background-wrapper-modal').fadeIn();

            //------------------------------------------------------------------ кнопка сброса задачи
            $(document).on('click', '#close-btn_' + tasksCounter, function () {
                $(backgroundWrapper).fadeOut();
                $(backgroundTaskArea).fadeOut();
                tasksPool[taskId].removeTask();
            });

            //------------------------------------------------------------------ старт задачи
            tasksPool[taskId].start();
            break;
        case 'popup':
            break;
    }

}

function drawTaskWindow(id, title) {
    var taskWindow = "" +
        '<div id="backgroundWrapper_'+ id + '">\n' +
        '    <div id="backgroundTaskArea_' + id + '" style="display: none">\n' +
        '        <div class="container-fluid">\n' +
        '            <div class="row">\n' +
        '                <div class="info-header">\n' +
        '                    <div class="col-lg-11">\n' +
        '                        <span id="info-header" class="info-header-text">' + title + '</span>\n' +
        '                    </div>\n' +
        '                    <div class="col-lg-1" align="right">\n' +
        '                        <button id="close-btn_' + id + '" class="btn-control glyphicon glyphicon-remove" ></button>\n' +
        '                    </div>\n' +
        '                </div>\n' +
        '            </div>\n' +
        '            <div class="row to-hide_' + id + '">\n' +
        '                <div class="col-lg-6">\n' +
        '                    <div class="infoArea" id="taskStatusArea_' + id + '"></div>\n' +
        '                </div>\n' +
        '                <div class="col-lg-6">\n' +
        '                    <div class="infoArea" id="customStatusArea_' + id + '"></div>\n' +
        '                </div>\n' +
        '            </div>\n' +
        '            <div class="row">\n' +
        '                <div class="col-lg-12">\n' +
        '                    <progress id="progressArea_' + id + '" max="100" value="0" style="width: 100%"></progress>\n' +
        '                </div>\n' +
        '            </div>\n' +
        '            <div class="row  to-hide_' + id + '">\n' +
        '                <div class="col-lg-12">\n' +
        '                    <div id="resultArea_'  + id + '" class="result-area" style="display: none"></div>\n' +
        '                </div>\n' +
        '            </div>\n' +
        '            <div class="row  to-hide_<?=$id?>">\n' +
        '                <div class="col-lg-12">\n' +
        '                    <div id="errorsArea_' + id + '" class="result-area" style="display: none"></div>\n' +
        '                </div>\n' +
        '            </div>\n' +
        '        </div>\n' +
        '    </div>\n' +
        '</div>\n';

    $('body').append(taskWindow);
}