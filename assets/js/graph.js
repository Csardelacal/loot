/* 
 * The MIT License
 *
 * Copyright 2019 César de la Cal Bretschneider <cesar@magic3w.com>.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */
depend(['m3/animation/bezier'], function (bezier) {
	
	
	
	
	return function (data, canvas) {
		
		canvas.height = canvas.clientHeight;
		canvas.width  = canvas.clientWidth;
		
		var min = Math.min.apply(null, data);
		var max = Math.max.apply(null, data);
		var maxH = canvas.height * 0.8;
		var ctx = canvas.getContext("2d");
		
		ctx.moveTo(0, canvas.height);
		ctx.beginPath();
		
		for (var i = 0; i < data.length - 1; i++) {
			var x1 = i * canvas.width / (data.length - 1);
			var x2 = (i +  .5) * canvas.width / (data.length - 1);
			var x3 = (i +   1) * canvas.width / (data.length - 1);
			var y1 = canvas.height - (data[i] - min) / (max - min) * maxH - (canvas.height - maxH) / 2;
			var y2 = canvas.height - (data[i+1] - min) / (max - min) * maxH - (canvas.height - maxH) / 2;
			
			var curve = bezier.bezier(bezier.point(x1, y1), bezier.point(x2, y1), bezier.point(x2, y2), bezier.point(x3, y2));
			for (var j = 0; j < 1.05; j+=.05) {
				ctx.lineTo(curve.x(j), curve.y(j));
				console.log(j, curve.x(j), curve.y(j));
			}
		}
		
		ctx.lineTo(canvas.width, canvas.height);
		ctx.lineTo(0, canvas.height);
		ctx.closePath();
		
		ctx.lineWidth   = 3;
		ctx.strokeStyle = '#72B63C';
		ctx.fillStyle   = '#D0E9BC';
		ctx.stroke();
		ctx.fill();
	}
});
