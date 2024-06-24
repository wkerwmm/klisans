from flask import Flask, render_template, request, redirect, url_for, jsonify
import os
import json
import random

app = Flask(__name__)
app.config['TEMPLATES_AUTO_RELOAD'] = True

# File paths
students_file = 'data/students.json'
grades_file = 'data/grades.json'
attendance_file = 'data/attendance.json'

def load_data(file):
    if os.path.exists(file):
        with open(file, 'r') as f:
            return json.load(f)
    else:
        return {}

def save_data(file, data):
    with open(file, 'w') as f:
        json.dump(data, f, indent=4)

def generate_student_id():
    return random.randint(100, 9999)

def get_valid_input(prompt):
    value = request.form.get(prompt)
    if value == '-':
        return None
    try:
        return float(value)
    except ValueError:
        return None

# Routes and functions for each operation

@app.route('/')
def index():
    return render_template('base.html')

@app.route('/add_student', methods=['GET', 'POST'])
def add_student():
    if request.method == 'POST':
        students = load_data(students_file)
        attendance = load_data(attendance_file)

        name = request.form['name']
        student_id = generate_student_id()
        student_number = request.form['student_number']
        birth_date = request.form['birth_date']
        address = request.form['address']
        phone = request.form['phone']
        email = request.form['email']

        students[student_id] = {
            "name": name,
            "student_number": student_number,
            "birth_date": birth_date,
            "address": address,
            "phone": phone,
            "email": email
        }
        attendance[student_id] = {
            "total": 0,
            "özürsüz": 0,
            "özürlü_total": 0,
            "izinli": 0,
            "raporlu": 0,
            "sevk": 0,
            "geç": 0,
            "faaliyet": 0
        }

        save_data(students_file, students)
        save_data(attendance_file, attendance)

        return redirect(url_for('list_students'))

    return render_template('add_student.html')

@app.route('/add_grade', methods=['GET', 'POST'])
def add_grade():
    if request.method == 'POST':
        students = load_data(students_file)
        grades = load_data(grades_file)

        student_id = request.form['student_id']

        if student_id in students:
            subject = request.form['subject']
            grade1 = get_valid_input('grade1')
            grade2 = get_valid_input('grade2')
            performance1 = get_valid_input('performance1')
            performance2 = get_valid_input('performance2')
            project1 = get_valid_input('project1')
            project2 = get_valid_input('project2')

            if student_id not in grades:
                grades[student_id] = []

            grades[student_id].append({
                "subject": subject,
                "grade1": grade1,
                "grade2": grade2,
                "performance1": performance1,
                "performance2": performance2,
                "project1": project1,
                "project2": project2
            })

            save_data(grades_file, grades)

            return redirect(url_for('index'))

    return render_template('add_grade.html')

@app.route('/add_attendance', methods=['GET', 'POST'])
def add_attendance():
    if request.method == 'POST':
        students = load_data(students_file)
        attendance = load_data(attendance_file)

        student_id = request.form['student_id']

        if student_id in students:
            attendance_type = request.form['attendance_type'].lower()

            if attendance_type == 'özürsüz':
                days = int(request.form['days'])
                attendance[student_id]['özürsüz'] += days
                attendance[student_id]['total'] += days
            elif attendance_type == 'özürlü':
                sub_type = request.form['sub_type'].lower()
                days = int(request.form['days'])

                if sub_type in ['izinli', 'raporlu', 'sevk']:
                    attendance[student_id][sub_type] += days
                    attendance[student_id]['özürlü_total'] += days
                    attendance[student_id]['total'] += days

            elif attendance_type == 'geç':
                count = int(request.form['count'])
                attendance[student_id]['geç'] += count

            elif attendance_type == 'faaliyet':
                count = int(request.form['count'])
                attendance[student_id]['faaliyet'] += count

            save_data(attendance_file, attendance)

            return redirect(url_for('index'))

    return render_template('add_attendance.html')

@app.route('/view_student', methods=['GET', 'POST'])
def view_student():
    if request.method == 'POST':
        students = load_data(students_file)
        attendance = load_data(attendance_file)
        grades = load_data(grades_file)

        student_id = request.form['student_id']

        if student_id in students:
            student = students[student_id]

            att = attendance.get(student_id, {})

            student_grades = grades.get(student_id, [])

            return render_template('view_student.html', student=student, attendance=att, grades=student_grades)

    return render_template('view_student.html')

@app.route('/list_students')
def list_students():
    students = load_data(students_file)
    return render_template('list_students.html', students=students)

@app.route('/delete_student/<student_id>')
def delete_student(student_id):
    students = load_data(students_file)
    grades = load_data(grades_file)
    attendance = load_data(attendance_file)

    if student_id in students:
        del students[student_id]
        grades.pop(student_id, None)
        attendance.pop(student_id, None)

        save_data(students_file, students)
        save_data(grades_file, grades)
        save_data(attendance_file, attendance)

    return redirect(url_for('list_students'))

if __name__ == '__main__':
    app.run(debug=True)
