var data = []
    , totalPoints = 300;
function getRandomData() {
    if (data.length > 0) data = data.slice(1);
    while (data.length < totalPoints) {
        var prev = data.length > 0 ? data[data.length - 1] : 50
            , y = prev + Math.random() * 10 - 5;
        if (y < 0) {
            y = 0;
        }
        else if (y > 100) {
            y = 100;
        }
        data.push(y);
    }
    var res = [];
    for (var i = 0; i < data.length; ++i) {
        res.push([i, data[i]])
    }
    return res;
}
var updateInterval = 30;
$("#updateInterval").val(updateInterval).on('change', function () {
    var v = $(this).val();
    if (v && !isNaN(+v)) {
        updateInterval = +v;
        if (updateInterval < 1) {
            updateInterval = 1;
        }
        else if (updateInterval > 3000) {
            updateInterval = 3000;
        }
        $(this).val("" + updateInterval);
    }
});
var plot = $.plot("#placeholder", [getRandomData()], {
    series: {
        shadowSize: 0 // Drawing is faster without shadows
    }
    , yaxis: {
        min: 0
        , max: 100
    }
    , xaxis: {
        show: false
    }
    , colors: ["#26c6da"]
    , grid: {
        color: "#AFAFAF"
        , hoverable: true
        , borderWidth: 0
        , backgroundColor: '#FFF'
    }
    , tooltip: true
    , tooltipOpts: {
        content: "Y: %y"
        , defaultTheme: false
    }
});
function update() {
    plot.setData([getRandomData()]);
    plot.draw();
    setTimeout(update, updateInterval);
}
update();
$(function () {
    var d1 = [];
    for (var i = 0; i <= 45; i += 1) d1.push([i, parseInt(Math.random() * 60)]);
    var d2 = [];
    for (var i = 0; i <= 45; i += 1) d2.push([i, parseInt(Math.random() * 40)]);
    var d3 = [];
    for (var i = 0; i <= 45; i += 1) d3.push([i, parseInt(Math.random() * 25)]);
    var ds = new Array();
    ds.push({
        label: "Data One"
        , data: d1
        , bars: {
            order: 1
        }
    });
    ds.push({
        label: "Data Two"
        , data: d2
        , bars: {
            order: 2
        }
    });
    ds.push({
        label: "Data Three"
        , data: d3
        , bars: {
            order: 3
        }
    });
    var stack = 0
        , bars = true
        , lines = true
        , steps = true;
    var options = {
        bars: {
            show: true
            , barWidth: 0.3
            , fill: 1
        }
        , grid: {
            show: true
            , aboveData: false
            , labelMargin: 1
            , axisMargin: 0
            , borderWidth: 1
            , minBorderMargin: 1
            , clickable: true
            , hoverable: true
            , autoHighlight: false
            , mouseActiveRadius: 20
            , borderColor: '#f5f5f5'
        }
        , series: {
            stack: stack
        }
        , legend: {
            position: "ne"
            , margin: [0, 0]
            , noColumns: 0
            , labelBoxBorderColor: null
            , labelFormatter: function (label, series) {
                // just add some space to labes
                return '' + label + '&nbsp;&nbsp;';
            }
            , width: 30
            , height: 5
        }
        , yaxis: {
            tickColor: '#f5f5f5'
            , font: {
                color: '#bdbdbd'
            }
        }
        , xaxis: {
            tickColor: '#f5f5f5'
            , font: {
                color: '#bdbdbd'
            }
        }
        , colors: ["#b3e5fc", "#4fc3f7", "#03a9f4"]
        , tooltip: true,
        tooltipOpts: {
            content: "%s : %y.0"
            , shifts: {
                x: -30
                , y: -50
            }
        }
    };
    $.plot($(".sales-bars-chart"), ds, options);
});
$(function() {
	$('[data-plugin="knob"]').knob();
});