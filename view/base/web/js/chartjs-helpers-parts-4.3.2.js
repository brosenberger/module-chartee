define([
], function(
) {
    "use strict";
    const intlCache = new Map();
    const isArray = function(value) {
        if (Array.isArray && Array.isArray(value)) {
            return true;
        }
        const type = Object.prototype.toString.call(value);
        if (type.slice(0, 7) === '[object' && type.slice(-6) === 'Array]') {
            return true;
        }
        return false;
    }
    const isObject = function(value) {
        return value !== null && Object.prototype.toString.call(value) === '[object Object]';
    }
    const clone = function(source) {
        if (isArray(source)) {
            return source.map(clone);
        }
        if (isObject(source)) {
            const target = Object.create(null);
            const keys = Object.keys(source);
            const klen = keys.length;
            let k = 0;
            for(; k < klen; ++k){
                target[keys[k]] = clone(source[keys[k]]);
            }
            return target;
        }
        return source;
    }

    const PI = Math.PI;
    const TAU = 2 * PI;
    const PITAU = TAU + PI;
    const INFINITY = Number.POSITIVE_INFINITY;
    const RAD_PER_DEG = PI / 180;
    const HALF_PI = PI / 2;
    const QUARTER_PI = PI / 4;
    const TWO_THIRDS_PI = PI * 2 / 3;
    const log10 = Math.log10;
    const sign = Math.sign;

    const isValidKey = function(key) {
        return [
            '__proto__',
            'prototype',
            'constructor'
        ].indexOf(key) === -1;
    };

    return {
        merge: function(target, source, options) {
            const sources = isArray(source) ? source : [
                source
            ];
            const ilen = sources.length;
            if (!isObject(target)) {
                return target;
            }
            options = options || {};
            var that = this;
            const merger = options.merger || function (key, target, source, options) {
                if (!isValidKey(key)) {
                    return;
                }
                const tval = target[key];
                const sval = source[key];
                if (isObject(tval) && isObject(sval)) {
                    // eslint-disable-next-line @typescript-eslint/no-use-before-define
                    that.merge(tval, sval, options);
                } else {
                    target[key] = clone(sval);
                }
            };
            let current;
            for(let i = 0; i < ilen; ++i){
                current = sources[i];
                if (!isObject(current)) {
                    continue;
                }
                const keys = Object.keys(current);
                for(let k = 0, klen = keys.length; k < klen; ++k){
                    merger(keys[k], target, current, options);
                }
            }
            return target;
        },
        formatNumber: function (num, locale, options) {
            return this.getNumberFormat(locale, options).format(num);
        },
        getNumberFormat: function(locale, options) {
            options = options || {};
            const cacheKey = locale + JSON.stringify(options);
            let formatter = intlCache.get(cacheKey);
            if (!formatter) {
                formatter = new Intl.NumberFormat(locale, options);
                intlCache.set(cacheKey, formatter);
            }
            return formatter;
        },
         drawPoint: function(ctx, options, x, y) {
            // eslint-disable-next-line @typescript-eslint/no-use-before-define
            this.drawPointLegend(ctx, options, x, y, null);
        },
        // eslint-disable-next-line complexity
        drawPointLegend: function(ctx, options, x, y, w) {
            let type, xOffset, yOffset, size, cornerRadius, width, xOffsetW, yOffsetW;
            const style = options.pointStyle;
            const rotation = options.rotation;
            const radius = options.radius;
            let rad = (rotation || 0) * RAD_PER_DEG;
            if (style && typeof style === 'object') {
                type = style.toString();
                if (type === '[object HTMLImageElement]' || type === '[object HTMLCanvasElement]') {
                    ctx.save();
                    ctx.translate(x, y);
                    ctx.rotate(rad);
                    ctx.drawImage(style, -style.width / 2, -style.height / 2, style.width, style.height);
                    ctx.restore();
                    return;
                }
            }
            if (isNaN(radius) || radius <= 0) {
                return;
            }
            ctx.beginPath();
            switch(style){
                // Default includes circle
                default:
                    if (w) {
                        ctx.ellipse(x, y, w / 2, radius, 0, 0, TAU);
                    } else {
                        ctx.arc(x, y, radius, 0, TAU);
                    }
                    ctx.closePath();
                    break;
                case 'triangle':
                    width = w ? w / 2 : radius;
                    ctx.moveTo(x + Math.sin(rad) * width, y - Math.cos(rad) * radius);
                    rad += TWO_THIRDS_PI;
                    ctx.lineTo(x + Math.sin(rad) * width, y - Math.cos(rad) * radius);
                    rad += TWO_THIRDS_PI;
                    ctx.lineTo(x + Math.sin(rad) * width, y - Math.cos(rad) * radius);
                    ctx.closePath();
                    break;
                case 'rectRounded':
                    // NOTE: the rounded rect implementation changed to use `arc` instead of
                    // `quadraticCurveTo` since it generates better results when rect is
                    // almost a circle. 0.516 (instead of 0.5) produces results with visually
                    // closer proportion to the previous impl and it is inscribed in the
                    // circle with `radius`. For more details, see the following PRs:
                    // https://github.com/chartjs/Chart.js/issues/5597
                    // https://github.com/chartjs/Chart.js/issues/5858
                    cornerRadius = radius * 0.516;
                    size = radius - cornerRadius;
                    xOffset = Math.cos(rad + QUARTER_PI) * size;
                    xOffsetW = Math.cos(rad + QUARTER_PI) * (w ? w / 2 - cornerRadius : size);
                    yOffset = Math.sin(rad + QUARTER_PI) * size;
                    yOffsetW = Math.sin(rad + QUARTER_PI) * (w ? w / 2 - cornerRadius : size);
                    ctx.arc(x - xOffsetW, y - yOffset, cornerRadius, rad - PI, rad - HALF_PI);
                    ctx.arc(x + yOffsetW, y - xOffset, cornerRadius, rad - HALF_PI, rad);
                    ctx.arc(x + xOffsetW, y + yOffset, cornerRadius, rad, rad + HALF_PI);
                    ctx.arc(x - yOffsetW, y + xOffset, cornerRadius, rad + HALF_PI, rad + PI);
                    ctx.closePath();
                    break;
                case 'rect':
                    if (!rotation) {
                        size = Math.SQRT1_2 * radius;
                        width = w ? w / 2 : size;
                        ctx.rect(x - width, y - size, 2 * width, 2 * size);
                        break;
                    }
                    rad += QUARTER_PI;
                /* falls through */ case 'rectRot':
                    xOffsetW = Math.cos(rad) * (w ? w / 2 : radius);
                    xOffset = Math.cos(rad) * radius;
                    yOffset = Math.sin(rad) * radius;
                    yOffsetW = Math.sin(rad) * (w ? w / 2 : radius);
                    ctx.moveTo(x - xOffsetW, y - yOffset);
                    ctx.lineTo(x + yOffsetW, y - xOffset);
                    ctx.lineTo(x + xOffsetW, y + yOffset);
                    ctx.lineTo(x - yOffsetW, y + xOffset);
                    ctx.closePath();
                    break;
                case 'crossRot':
                    rad += QUARTER_PI;
                /* falls through */ case 'cross':
                    xOffsetW = Math.cos(rad) * (w ? w / 2 : radius);
                    xOffset = Math.cos(rad) * radius;
                    yOffset = Math.sin(rad) * radius;
                    yOffsetW = Math.sin(rad) * (w ? w / 2 : radius);
                    ctx.moveTo(x - xOffsetW, y - yOffset);
                    ctx.lineTo(x + xOffsetW, y + yOffset);
                    ctx.moveTo(x + yOffsetW, y - xOffset);
                    ctx.lineTo(x - yOffsetW, y + xOffset);
                    break;
                case 'star':
                    xOffsetW = Math.cos(rad) * (w ? w / 2 : radius);
                    xOffset = Math.cos(rad) * radius;
                    yOffset = Math.sin(rad) * radius;
                    yOffsetW = Math.sin(rad) * (w ? w / 2 : radius);
                    ctx.moveTo(x - xOffsetW, y - yOffset);
                    ctx.lineTo(x + xOffsetW, y + yOffset);
                    ctx.moveTo(x + yOffsetW, y - xOffset);
                    ctx.lineTo(x - yOffsetW, y + xOffset);
                    rad += QUARTER_PI;
                    xOffsetW = Math.cos(rad) * (w ? w / 2 : radius);
                    xOffset = Math.cos(rad) * radius;
                    yOffset = Math.sin(rad) * radius;
                    yOffsetW = Math.sin(rad) * (w ? w / 2 : radius);
                    ctx.moveTo(x - xOffsetW, y - yOffset);
                    ctx.lineTo(x + xOffsetW, y + yOffset);
                    ctx.moveTo(x + yOffsetW, y - xOffset);
                    ctx.lineTo(x - yOffsetW, y + xOffset);
                    break;
                case 'line':
                    xOffset = w ? w / 2 : Math.cos(rad) * radius;
                    yOffset = Math.sin(rad) * radius;
                    ctx.moveTo(x - xOffset, y - yOffset);
                    ctx.lineTo(x + xOffset, y + yOffset);
                    break;
                case 'dash':
                    ctx.moveTo(x, y);
                    ctx.lineTo(x + Math.cos(rad) * (w ? w / 2 : radius), y + Math.sin(rad) * radius);
                    break;
                case false:
                    ctx.closePath();
                    break;
            }
            ctx.fill();
            if (options.borderWidth > 0) {
                ctx.stroke();
            }
        },
        isArray: function (value) {
            if (Array.isArray && Array.isArray(value)) {
                return true;
            }
            const type = Object.prototype.toString.call(value);
            if (type.slice(0, 7) === '[object' && type.slice(-6) === 'Array]') {
                return true;
            }
            return false;
        },

        _merger: function (key, target, source, options) {
            if (!this.isValidKey(key)) {
                return;
            }
            const tval = target[key];
            const sval = source[key];
            if (this.isObject(tval) && this.isObject(sval)) {
                // eslint-disable-next-line @typescript-eslint/no-use-before-define
                this.merge(tval, sval, options);
            } else {
                target[key] = clone(sval);
            }
        }
    };
});
