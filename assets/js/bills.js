// bills.js
document.addEventListener('DOMContentLoaded', function() {
    const groupCountSelect = document.getElementById('group_count');
    const groupContainer = document.getElementById('groupContainer');

    // ฟังก์ชันสร้างกลุ่ม
    function generateGroups() {
        const groupCount = parseInt(groupCountSelect.value, 10);
        groupContainer.innerHTML = ''; // ล้างคอนเทนเนอร์

        for (let i = 0; i < groupCount; i++) {
            const groupDiv = document.createElement('div');
            groupDiv.classList.add('card', 'mb-3', 'p-3');
            groupDiv.innerHTML = `
                <h5>กลุ่มที่ ${i + 1}</h5>
                
                    <div class="col-md-6 mb-3">
                        <label class="form-label">กลุ่มลำดับที่</label>
                        <select name="groups[${i}][type]" class="form-select" required>
                            <option value="1">1</option>
                            <option value="2">2</option>
                            <option value="3">3</option>
                        </select>
                    </div>

                    <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">ชื่อกลุ่ม</label>
                        <input type="text" name="groups[${i}][name]" class="form-control" required>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">ราคาแพ็คเกจหลัก</label>
                        <input type="number" name="groups[${i}][price_a]" class="form-control" required min="0" step="0.01">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label">ราคา ICT Solution</label>
                        <input type="number" name="groups[${i}][price_b]" class="form-control" required min="0" step="0.01">
                    </div>
                    <div class="col-md-12 mb-3">
                        <label class="form-label">ข้อมูลเพิ่มเติม</label>
                        <div id="infoContainer_${i}">
                            <div class="input-group mb-2 info-group">
                                <input type="text" name="groups[${i}][info][]" class="form-control" placeholder="กรอกข้อมูลหมายเลขบริการ">
                                <button type="button" class="btn btn-danger remove-info-btn">ลบ</button>
                            </div>
                        </div>
                        <button type="button" class="btn btn-secondary add-info-btn" data-group-index="${i}">เพิ่มข้อมูล</button>
                    </div>
                </div>
            `;
            groupContainer.appendChild(groupDiv);
        }

        // เพิ่ม Event Listener สำหรับปุ่มเพิ่มข้อมูลกลุ่ม
        const addInfoButtons = document.querySelectorAll('.add-info-btn');
        addInfoButtons.forEach(button => {
            button.addEventListener('click', function() {
                const groupIndex = this.getAttribute('data-group-index');
                addInfoField(groupIndex);
            });
        });

        // เพิ่ม Event Listener สำหรับปุ่มลบข้อมูลกลุ่ม
        const removeInfoButtons = document.querySelectorAll('.remove-info-btn');
        removeInfoButtons.forEach(button => {
            button.addEventListener('click', function() {
                this.parentElement.remove();
            });
        });
    }

    // ฟังก์ชันเพิ่มฟิลด์ข้อมูลกลุ่ม
    function addInfoField(groupIndex) {
        const infoContainer = document.getElementById(`infoContainer_${groupIndex}`);
        const infoGroupDiv = document.createElement('div');
        infoGroupDiv.classList.add('input-group', 'mb-2', 'info-group');
        infoGroupDiv.innerHTML = `
            <input type="text" name="groups[${groupIndex}][info][]" class="form-control" placeholder="ข้อมูลเพิ่มเติม">
            <button type="button" class="btn btn-danger remove-info-btn">ลบ</button>
        `;
        infoContainer.appendChild(infoGroupDiv);

        // เพิ่ม Event Listener สำหรับปุ่มลบ
        const removeButton = infoGroupDiv.querySelector('.remove-info-btn');
        removeButton.addEventListener('click', function() {
            this.parentElement.remove();
        });
    }

    // สร้างกลุ่มเริ่มแรก
    generateGroups();

    // เปลี่ยนแปลงจำนวนกลุ่ม
    groupCountSelect.addEventListener('change', generateGroups);

    
});

