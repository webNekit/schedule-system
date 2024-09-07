document.querySelectorAll('.teacherName').forEach(element => {
    const fullName = element.textContent.trim();
    const nameParts = fullName.split(' ');

    if(nameParts.length === 3) {
        const formattedName = `${nameParts[0]} ${nameParts[1][0]}.${nameParts[2][0]}.`;
        element.textContent = formattedName;
    }
});

document.querySelectorAll('.roomValue').forEach(element => {
    const roomText = element.textContent.trim();
    const roomParts = roomText.split(' ');

    if(roomParts.length === 4 && roomParts[1] === 'этаж' && roomParts[3] === 'кабинет') {
        const formattedRoom = `${roomParts[0]} эт. ${roomParts[2]} кб.`;
        element.textContent = formattedRoom;
    }
});

document.querySelectorAll('.subjectName').forEach(element => {
    const subjectText = element.textContent.trim();
    const dotCount = (subjectText.match(/\./g) || []).length;

    if (dotCount === 3) {
        const parts = subjectText.split(' ');
        element.textContent = parts[0];
    }
});

document.getElementById('convertBtn').addEventListener('click', function() {
    const element = document.getElementById('schedules-wrapper');
  
    html2canvas(element, {
      useCORS: true,   // Для поддержки кросс-доменных ресурсов
      scale: 2         // Увеличивает разрешение для улучшения качества
    }).then(function(canvas) {
      const link = document.createElement('a');
      link.href = canvas.toDataURL('image/png');
      link.download = 'schedules.png';
      link.click();
    });
  });
  