function formatterName(){
    document.querySelectorAll('.teacherName').forEach(element => {
        const fullName = element.textContent.trim();
        const nameParts = fullName.split(' ');

        if(nameParts.length === 3) {
            const formattedName = `${nameParts[0]} ${nameParts[1][0]}.${nameParts[2][0]}.`;
            element.textContent = formattedName;
        }
    });
}

export default formatterName;