using System;
using System.Collections.Generic;
using System.Text;

namespace Gibbinator.Models
{
    class Task
    {
        public int TaskId { get; set; }
        public string Title { get; set; }
        public string Description { get; set; }
        public TaskType TaskType { get; set; }
    }
}
