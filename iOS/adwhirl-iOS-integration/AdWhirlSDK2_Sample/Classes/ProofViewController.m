//
//  ProofViewController.m
//  AdWhirlSDK2_Sample
//
//  Created by Nigel Choi on 3/11/10.
//  Copyright 2010 Admob. Inc. All rights reserved.
//

#import "ProofViewController.h"


@implementation ProofViewController

@synthesize url;

- (id)init {
  if (self = [super initWithNibName:@"ProofViewController" bundle:nil]) {
    self.title = @"Proof View";
    if ([self respondsToSelector:@selector(setModalTransitionStyle)]) {
      [self setModalTransitionStyle:UIModalTransitionStyleCoverVertical];
    }
  }
  return self;
}
- (id) initWithNibName:(NSString *)nibNameOrNil bundle:(NSBundle *)nibBundleOrNil initWithParam:(NSURL *)url {
    if (self = [super initWithNibName:nibNameOrNil bundle:nibBundleOrNil]) {
      NSLog(@"\n\n\nURL = %@\n\n\n", url);
      self.url = url;
    }
    return self;
}
/*
 // The designated initializer.  Override if you create the controller programmatically and want to perform customization that is not appropriate for viewDidLoad.
- (id)initWithNibName:(NSString *)nibNameOrNil bundle:(NSBundle *)nibBundleOrNil {
    if ((self = [super initWithNibName:nibNameOrNil bundle:nibBundleOrNil])) {
        // Custom initialization
    }
    return self;
}
*/

// Implement viewDidLoad to do additional setup after loading the view, typically from a nib.
- (void)viewDidLoad {
    NSLog(@"\n\n\nPROOF VIEW LOADED\n\n\n");
    NSLog(@"\n\n\nURL = %@\n\n\n", url);

    [super viewDidLoad];
    UIView* wrapper = [[[UIView alloc] initWithFrame:CGRectMake(0, 50, 320, 480)] autorelease];
    UIWebView* browser = [[[UIWebView alloc] initWithFrame:CGRectMake(0, 0, 320, 480)] autorelease];
    [wrapper addSubview:browser];
    [browser setDelegate:self];
    browser.scalesPageToFit = YES;
    [browser loadRequest:[NSURLRequest requestWithURL:url]];

    [self.view addSubview:wrapper];
}

- (BOOL)shouldAutorotateToInterfaceOrientation:(UIInterfaceOrientation)interfaceOrientation {
  return YES;
}

- (IBAction)dismiss:(id)sender {
	[self dismissModalViewControllerAnimated:YES];
}

- (void)didReceiveMemoryWarning {
    // Releases the view if it doesn't have a superview.
    [super didReceiveMemoryWarning];
    
    // Release any cached data, images, etc that aren't in use.
}

- (void)viewDidUnload {
    [super viewDidUnload];
    // Release any retained subviews of the main view.
    // e.g. self.myOutlet = nil;
}


- (void)dealloc {
    [super dealloc];
}
@end
