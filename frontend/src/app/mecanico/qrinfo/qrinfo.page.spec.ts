import { ComponentFixture, TestBed } from '@angular/core/testing';
import { QrinfoPage } from './qrinfo.page';

describe('QrinfoPage', () => {
  let component: QrinfoPage;
  let fixture: ComponentFixture<QrinfoPage>;

  beforeEach(() => {
    fixture = TestBed.createComponent(QrinfoPage);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
