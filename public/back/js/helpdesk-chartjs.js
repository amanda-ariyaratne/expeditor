! function(r) {
    var e = {};

    function o(t) {
        if (e[t]) return e[t].exports;
        var a = e[t] = {
            i: t,
            l: !1,
            exports: {}
        };
        return r[t].call(a.exports, a, a.exports, o), a.l = !0, a.exports
    }
    o.m = r, o.c = e, o.d = function(r, e, t) {
        o.o(r, e) || Object.defineProperty(r, e, {
            enumerable: !0,
            get: t
        })
    }, o.r = function(r) {
        "undefined" != typeof Symbol && Symbol.toStringTag && Object.defineProperty(r, Symbol.toStringTag, {
            value: "Module"
        }), Object.defineProperty(r, "__esModule", {
            value: !0
        })
    }, o.t = function(r, e) {
        if (1 & e && (r = o(r)), 8 & e) return r;
        if (4 & e && "object" == typeof r && r && r.__esModule) return r;
        var t = Object.create(null);
        if (o.r(t), Object.defineProperty(t, "default", {
                enumerable: !0,
                value: r
            }), 2 & e && "string" != typeof r)
            for (var a in r) o.d(t, a, function(e) {
                return r[e]
            }.bind(null, a));
        return t
    }, o.n = function(r) {
        var e = r && r.__esModule ? function() {
            return r.default
        } : function() {
            return r
        };
        return o.d(e, "a", e), e
    }, o.o = function(r, e) {
        return Object.prototype.hasOwnProperty.call(r, e)
    }, o.p = "", o(o.s = 18)
}({
    18: function(r, e, o) {
        r.exports = o(19)
    },
    19: function(r, e) {
        function o(r, e) {
            for (var o = 0; o < e.length; o++) {
                var t = e[o];
                t.enumerable = t.enumerable || !1, t.configurable = !0, "value" in t && (t.writable = !0), Object.defineProperty(r, t.key, t)
            }
        }
        var t = function() {
            function r() {
                ! function(r, e) {
                    if (!(r instanceof e)) throw new TypeError("Cannot call a class as a function")
                }(this, r)
            }
            var e, t, a;
            return e = r, a = [{
                key: "initCharts",
                value: function() {
                    Chart.defaults.global.defaultFontColor = "#495057", Chart.defaults.scale.gridLines.color = "transparent", Chart.defaults.scale.gridLines.zeroLineColor = "transparent", Chart.defaults.scale.display = !1, Chart.defaults.scale.ticks.beginAtZero = !0, Chart.defaults.global.elements.line.borderWidth = 0, Chart.defaults.global.elements.point.radius = 0, Chart.defaults.global.elements.point.hoverRadius = 0, Chart.defaults.global.tooltips.cornerRadius = 3, Chart.defaults.global.legend.labels.boxWidth = 12;
                    var r, e, o, t, a = jQuery(".js-chartjs-dashboard-earnings"),
                        n = jQuery(".js-chartjs-dashboard-sales");
                    r = {
                        maintainAspectRatio: !1,
                        scales: {
                            yAxes: [{
                                ticks: {
                                    suggestedMax: 3e3
                                }
                            }]
                        },
                        tooltips: {
                            intersect: !1,
                            callbacks: {
                                label: function(r, e) {
                                    return " $" + r.yLabel
                                }
                            }
                        }
                    }, o = {
                        maintainAspectRatio: !1,
                        scales: {
                            yAxes: [{
                                ticks: {
                                    suggestedMax: 260
                                }
                            }]
                        },
                        tooltips: {
                            intersect: !1,
                            callbacks: {
                                label: function(r, e) {
                                    return " " + r.yLabel + " Sales"
                                }
                            }
                        }
                    }, e = {
                        labels: ["JAN", "FEB", "MAR", "APR", "MAY", "JUN", "JUL", "AUG", "SEP", "OCT", "NOV", "DEC"],
                        datasets: [{
                            label: "This Year",
                            fill: !0,
                            backgroundColor: "rgba(132, 94, 247, .3)",
                            borderColor: "transparent",
                            pointBackgroundColor: "rgba(132, 94, 247, 1)",
                            pointBorderColor: "#fff",
                            pointHoverBackgroundColor: "#fff",
                            pointHoverBorderColor: "rgba(132, 94, 247, 1)",
                            data: [2150, 1350, 1560, 980, 1260, 1720, 1115, 1690, 1870, 2420, 2100, 2730]
                        }, {
                            label: "Last Year",
                            fill: !0,
                            backgroundColor: "rgba(233, 236, 239, 1)",
                            borderColor: "transparent",
                            pointBackgroundColor: "rgba(233, 236, 239, 1)",
                            pointBorderColor: "#fff",
                            pointHoverBackgroundColor: "#fff",
                            pointHoverBorderColor: "rgba(233, 236, 239, 1)",
                            data: [2200, 1700, 1100, 1900, 1680, 2560, 1340, 1450, 2e3, 2500, 1550, 1880]
                        }]
                    }, t = {
                        labels: ["JAN", "FEB", "MAR", "APR", "MAY", "JUN", "JUL", "AUG", "SEP", "OCT", "NOV", "DEC"],
                        datasets: [{
                            label: "This Year",
                            fill: !0,
                            backgroundColor: "rgba(34, 184, 207, .3)",
                            borderColor: "transparent",
                            pointBackgroundColor: "rgba(34, 184, 207, 1)",
                            pointBorderColor: "#fff",
                            pointHoverBackgroundColor: "#fff",
                            pointHoverBorderColor: "rgba(34, 184, 207, 1)",
                            data: [175, 120, 169, 82, 135, 169, 132, 130, 192, 230, 215, 260]
                        }, {
                            label: "Last Year",
                            fill: !0,
                            backgroundColor: "rgba(233, 236, 239, 1)",
                            borderColor: "transparent",
                            pointBackgroundColor: "rgba(233, 236, 239, 1)",
                            pointBorderColor: "#fff",
                            pointHoverBackgroundColor: "#fff",
                            pointHoverBorderColor: "rgba(233, 236, 239, 1)",
                            data: [220, 170, 110, 215, 168, 227, 154, 135, 210, 240, 145, 178]
                        }]
                    }, a.length && new Chart(a, {
                        type: "line",
                        data: e,
                        options: r
                    }), n.length && new Chart(n, {
                        type: "line",
                        data: t,
                        options: o
                    })
                }
            }, {
                key: "init",
                value: function() {
                    this.initCharts()
                }
            }], (t = null) && o(e.prototype, t), a && o(e, a), r
        }();
        jQuery(function() {
            t.init()
        })
    }
});