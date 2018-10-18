using System;
using System.Collections.Generic;
using System.Linq;
using System.Threading.Tasks;
using Gibbinator.Models;

namespace Gibbinator.Services
{
    public class MockDataStore : IDataStore<Teacher>
    {
        List<Teacher> teachers;

        public MockDataStore()
        {
            teachers = new List<Teacher>();
            var mockTeachers = new List<Teacher>
            {
                new Teacher { TeacherId = 1, Name = "Isabel", Surname="Keller" , Email="isabel.keller@gibb.ch" , Telephone="079 000 00 00"},
                new Teacher { TeacherId = 2, Name = "Martin", Surname="Horst" , Email="martin.horst@gibb.ch" , Telephone="079 000 00 00"},
                new Teacher { TeacherId = 3, Name = "Martin", Surname="Schär" , Email="matthias.burkhardt@gibb.ch" , Telephone="079 000 00 00"},
                new Teacher { TeacherId = 4, Name = "Matthias", Surname="Burkhardt" , Email="peter.gfeller@gibb.ch" , Telephone="079 000 00 00"},
                new Teacher { TeacherId = 5, Name = "Peter", Surname="Gfeller" , Email="martin.shaer@gibb.ch" , Telephone="079 000 00 00"}
            };

            foreach (var teacher in mockTeachers)
            {
                teachers.Add(teacher);
            }
        }

        public async Task<bool> AddTeacherAsync(Teacher teacher)
        {
            teachers.Add(teacher);

            return await Task.FromResult(true);
        }

        public async Task<bool> UpdateTeacherAsync(Teacher teacher)
        {
            var oldTeacher = teachers.Where((Teacher arg) => arg.TeacherId == teacher.TeacherId).FirstOrDefault();
            teachers.Remove(oldTeacher);
            teachers.Add(teacher);

            return await Task.FromResult(true);
        }

        public async Task<bool> DeleteTeacherAsync(int id)
        {
            var oldItem = teachers.Where((Teacher arg) => arg.TeacherId == id).FirstOrDefault();
            teachers.Remove(oldItem);

            return await Task.FromResult(true);
        }

        public async Task<Teacher> GetTeacherAsync(int id)
        {
            return await Task.FromResult(teachers.FirstOrDefault(s => s.TeacherId == id));
        }

        public async Task<IEnumerable<Teacher>> GetTeachersAsync(bool forceRefresh = false)
        {
            return await Task.FromResult(teachers);
        }

    }
}