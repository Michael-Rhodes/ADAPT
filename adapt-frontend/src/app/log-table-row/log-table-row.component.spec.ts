import { async, ComponentFixture, TestBed } from '@angular/core/testing';

import { LogTableRowComponent } from './log-table-row.component';

describe('LogTableRowComponent', () => {
  let component: LogTableRowComponent;
  let fixture: ComponentFixture<LogTableRowComponent>;

  beforeEach(async(() => {
    TestBed.configureTestingModule({
      declarations: [ LogTableRowComponent ]
    })
    .compileComponents();
  }));

  beforeEach(() => {
    fixture = TestBed.createComponent(LogTableRowComponent);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
