using Gibbinator.Models;
using System;
using System.Collections.Generic;
using System.Text;

namespace Gibbinator.ViewModels
{
    public class LessonDetailViewModel : BaseViewModel
    {
        public Lesson Lesson { get; set; }
        public LessonDetailViewModel(Lesson lesson = null)
        {
            Title = lesson?.Description;
            Lesson = lesson;
        }

    }
}
