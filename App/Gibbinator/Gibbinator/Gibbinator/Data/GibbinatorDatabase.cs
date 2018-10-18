using Gibbinator.Models;
using SQLite;
using System;
using System.Collections.Generic;
using System.Text;
using System.Threading.Tasks;

namespace Gibbinator.Data
{
    public class GibbinatorDatabase
    {
        readonly SQLiteAsyncConnection database;

        public GibbinatorDatabase(string dbPath)
        {
            database = new SQLiteAsyncConnection(dbPath);
            database.CreateTableAsync<Lesson>().Wait();
        }

        public Task<List<Lesson>> GetItemsAsync()
        {
            return database.Table<Lesson>().ToListAsync();
        }

        public Task<List<Lesson>> GetItemsNotDoneAsync()
        {
            return database.QueryAsync<Lesson>("SELECT * FROM [TodoItem] WHERE [Done] = 0");
        }

        public Task<Lesson> GetItemAsync(int id)
        {
            return database.Table<Lesson>().Where(i => i.LessonId == id).FirstOrDefaultAsync();
        }

        public Task<int> SaveItemAsync(Lesson lesson)
        {
            if (lesson.LessonId != 0)
            {
                return database.UpdateAsync(lesson);
            }
            else
            {
                return database.InsertAsync(lesson);
            }
        }

        public Task<int> DeleteItemAsync(Lesson lesson)
        {
            return database.DeleteAsync(lesson);
        }
    }
}
