package md56a462996b33a3e9d22f9d04f97ec7ebe;


public class DraggingShadowBuilder
	extends android.view.View.DragShadowBuilder
	implements
		mono.android.IGCUserPeer
{
/** @hide */
	public static final String __md_methods;
	static {
		__md_methods = 
			"n_onProvideShadowMetrics:(Landroid/graphics/Point;Landroid/graphics/Point;)V:GetOnProvideShadowMetrics_Landroid_graphics_Point_Landroid_graphics_Point_Handler\n" +
			"";
		mono.android.Runtime.register ("Com.Syncfusion.Schedule.DraggingShadowBuilder, Syncfusion.SfSchedule.Android", DraggingShadowBuilder.class, __md_methods);
	}


	public DraggingShadowBuilder ()
	{
		super ();
		if (getClass () == DraggingShadowBuilder.class)
			mono.android.TypeManager.Activate ("Com.Syncfusion.Schedule.DraggingShadowBuilder, Syncfusion.SfSchedule.Android", "", this, new java.lang.Object[] {  });
	}


	public DraggingShadowBuilder (android.view.View p0)
	{
		super (p0);
		if (getClass () == DraggingShadowBuilder.class)
			mono.android.TypeManager.Activate ("Com.Syncfusion.Schedule.DraggingShadowBuilder, Syncfusion.SfSchedule.Android", "Android.Views.View, Mono.Android", this, new java.lang.Object[] { p0 });
	}


	public void onProvideShadowMetrics (android.graphics.Point p0, android.graphics.Point p1)
	{
		n_onProvideShadowMetrics (p0, p1);
	}

	private native void n_onProvideShadowMetrics (android.graphics.Point p0, android.graphics.Point p1);

	private java.util.ArrayList refList;
	public void monodroidAddReference (java.lang.Object obj)
	{
		if (refList == null)
			refList = new java.util.ArrayList ();
		refList.add (obj);
	}

	public void monodroidClearReferences ()
	{
		if (refList != null)
			refList.clear ();
	}
}
