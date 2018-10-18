using System;
using System.Collections.Generic;
using System.Threading.Tasks;

namespace Gibbinator.Services
{
    public interface IDataStore<T>
    {
        Task<bool> AddTeacherAsync(T teacher);
        Task<bool> UpdateTeacherAsync(T teacher);
        Task<bool> DeleteTeacherAsync(int id);
        Task<T> GetTeacherAsync(int id);
        Task<IEnumerable<T>> GetTeachersAsync(bool forceRefresh = false);
    }
}
