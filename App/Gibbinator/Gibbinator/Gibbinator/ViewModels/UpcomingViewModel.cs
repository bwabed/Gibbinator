using Gibbinator.Models;
using System;
using System.Collections.Generic;
using System.Collections.ObjectModel;
using System.Diagnostics;
using System.Text;
using System.Threading.Tasks;
using Xamarin.Forms;

namespace Gibbinator.ViewModels
{
    public class UpcomingViewModel : BaseViewModel
    {
        public ObservableCollection<Lesson> Lessons { get; set; }
        public Command LoadLessonsCommand { get; set; }

        public UpcomingViewModel()
        {
            Title = "Lehrerliste";
            Lessons = new ObservableCollection<Lesson>();
            LoadLessonsCommand = new Command(async () => await ExecuteLoadLessonsCommand());

        }

        async Task ExecuteLoadLessonsCommand()
        {
            if (IsBusy)
                return;

            IsBusy = true;

            try
            {
                Lessons.Clear();
                var lessons = await DataStoreLesson.GetTeachersAsync(true);
                foreach (var lesson in lessons)
                {
                    Lessons.Add(lesson);
                }
            }
            catch (Exception ex)
            {
                Debug.WriteLine(ex);
            }
            finally
            {
                IsBusy = false;
            }
        }
    }
}
