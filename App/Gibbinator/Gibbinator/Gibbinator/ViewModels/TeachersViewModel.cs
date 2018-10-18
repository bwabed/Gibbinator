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
    public class TeachersViewModel : BaseViewModel
    {
        public ObservableCollection<Teacher> Teachers { get; set; }
        public Command LoadTeachersCommand { get; set; }

        public TeachersViewModel()
        {
            Title = "Lehrerliste";
            Teachers = new ObservableCollection<Teacher>();
            LoadTeachersCommand = new Command(async () => await ExecuteLoadTeachersCommand());

        }

        async Task ExecuteLoadTeachersCommand()
        {
            if (IsBusy)
                return;

            IsBusy = true;

            try
            {
                Teachers.Clear();
                var teachers = await DataStore.GetTeachersAsync(true);
                foreach (var teacher in teachers)
                {
                    Teachers.Add(teacher);
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
