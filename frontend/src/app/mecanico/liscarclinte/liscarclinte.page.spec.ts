import { ComponentFixture, TestBed } from '@angular/core/testing';
import { LiscarclintePage } from './liscarclinte.page';

describe('LiscarclintePage', () => {
  let component: LiscarclintePage;
  let fixture: ComponentFixture<LiscarclintePage>;

  beforeEach(() => {
    fixture = TestBed.createComponent(LiscarclintePage);
    component = fixture.componentInstance;
    fixture.detectChanges();
  });

  it('should create', () => {
    expect(component).toBeTruthy();
  });
});
