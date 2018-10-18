using Gibbinator.Models;
using System;
using System.Collections.Generic;
using System.Text;
using Xamarin.Forms;

namespace Gibbinator.ViewModels
{
    public class TeacherDetailViewModel : BaseViewModel
    {
        public Teacher Teacher { get; set; }
        public TeacherDetailViewModel(Teacher teacher = null)
        {
            Title = teacher?.Name + " " + teacher?.Surname;
            Teacher = teacher;
        }

        public Command WriteEmail
        {
            get
            {
                return new Command(p => {
                    Device.OpenUri(new Uri(String.Format("mailto:{0}", p)));
                });
            }
        }

        public Command CallTelephone
        {
            get
            {
                return new Command(p => {
                    Device.OpenUri(new Uri(String.Format("tel:{0}", p)));
                });
            }
        }
    }
}
